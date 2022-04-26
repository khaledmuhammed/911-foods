<?php

namespace App\Http\Controllers\api;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Libraries\ApiResponse;
use App\Libraries\ApiValidator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Prettus\Validator\Exceptions\ValidatorException;

class MainController extends Controller
{
    function defaultCurrency()
    {
        return response()->json(setting('default_currency'));
    }
    function currencyRight()
    {
        return response()->json(setting('currency_right', false));
    }

    public function paymob_mobile_wallet(Request $request)
    {
        if (empty($request['order_id']) && !is_numeric($request['order_id'])) {
            return response('Order ID not found', 404)->header('Content-Type', 'text/plain');
        }
        // dd($request['order_id']);
        if(empty($request['phone_number'])){
            
            return response('Phone Number not found', 404)->header('Content-Type', 'text/plain');
        }

        $order = Order::where('id', $request['order_id'])->first();
        if (!$order) {
            return response('Order ID not Exiest', 404)->header('Content-Type', 'text/plain');
        }
        // dd($order);

        try {
            $user_phone = $request['phone_number'];

            $credit_link = $this->credit($order, $user_phone);
            // dd($credit_link);
            // return response($payment_token, 200)->header('Content-Type', 'application/json');
            return redirect()->away($credit_link);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
    }




    //start paymob getway functions 
    public function credit($db_order, $user_phone)
    {

        $token = $this->getToken();
        $order = $this->createOrder($db_order, $token);
        $paymentToken = $this->getPaymentToken($order, $db_order, $token);

        $mobile_response = $this->mobile_wallet_step($paymentToken, $user_phone);
        return $mobile_response;
        return redirect()->away('https://www.google.com');
        // header('Location: https://portal.weaccept.co/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken);
        // die();
    }

    public function getToken()
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => env('PAYMOB_API_KEY')
        ]);
        return $response->object()->token;
    }

    public function createOrder($order, $token)
    {

        $items = [];
        for ($i = 0; $i < count($order->productOrders); $i++) {

            $items[$i]['name'] = $order->productOrders[$i]->product->name;
            $items[$i]['amount_cents'] = $order->productOrders[$i]->product->price * 100;
            $items[$i]['description'] = strip_tags($order->productOrders[$i]->product->description);
            $items[$i]['quantity'] = $order->productOrders[$i]->quantity;
        }

        $data = [
            "auth_token" =>   $token,
            "delivery_needed" => "true",
            "amount_cents" => $order->payment->price * 100,
            "currency" => "EGP",
            "merchant_order_id" => $order->id,
            "items" => $items,
        ];

        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', $data);
        return $response->object();
    }

    public function getPaymentToken($order, $db_order, $token)
    {
        $billingData = [
            "apartment" => "803",
            "email" => $db_order->user->email,
            "floor" => "42",
            "first_name" => $db_order->user->name,
            "street" => "Ethan Land",
            "building" => "8028",
            "phone_number" => "+86(8)9135210487",
            "shipping_method" => "PKG",
            "postal_code" => "01898",
            "city" => "Jaskolskiburgh",
            "country" => "CR",
            "last_name" => "Nicolas",
            "state" => "Utah"
        ];

        $data = [
            "auth_token" => $token,
            "amount_cents" => $db_order->payment->price * 100,
            "expiration" => 3600,
            "order_id" => $order->id, //paymob order id
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => env('PAYMOB_INTEGRATION_ID')
        ];

        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', $data);

        return $response->object()->token;
    }

    public function mobile_wallet_step($token, $user_phone)
    {
        $source = [
            "identifier" => $user_phone,
            "subtype" => "WALLET"
        ];
        $paymentToken = $token;

        $data = [
            "source" => $source,
            "payment_token" => $paymentToken
        ];


        $response = Http::post('https://accept.paymob.com/api/acceptance/payments/pay', $data);
        dd($response->object());
        return $response->object();
    }


    public function callback(Request $request)
    {
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);

        $db_order = Order::where('id', $request['merchant_order_id'])->first();
        if ($hased == $hmac) {
            $db_order->update(['order_status_id' => 2]); // status 'Preparing'    
            $db_order->payment->update(['description' => 'Order Payed', 'status' => 'Paid']);

            return redirect("/order/confirm")->with('market', $db_order->productOrders[0]->product->market)->with('order', $db_order);
            exit;
        }
        $db_order->update(['order_status_id' => 7]); // status 'Canceled'
        Payment::where('id', $db_order->payment_id)->update(['description' => 'Order not Payed', 'status' => 'Not paid']);
        return redirect("/order/not-confirm")->with('message', "Order Not confirmed");
        exit;
    }
    //end paymob getway functions 


}

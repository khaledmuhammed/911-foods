<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartOption;
use App\Models\Coupon;
use App\Models\CustomFieldValue;
use App\Models\DeliveryAddresse;
use App\Models\Market;
use App\Models\Option;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\productOrder;
use App\Models\ProductOrderExtra;
use App\Models\ProductOrderOption;
use App\Notifications\NewOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index(Market $market)
    {
        $customFields = array();
        $userAdresses = array();
        $customFieldValue = auth()->user()->customFieldValue;
        if (count($customFieldValue) != 0) {
            foreach ($customFieldValue as $customField) {
                if ($customField->customField->isPhone()) {
                    $customFields["phone"] = ["value" => $customField->value, "view" => $customField->view];
                }
                if ($customField->customField->isAddress()) {
                    array_push($userAdresses, ["value" => $customField->value, "view" => $customField->view]);
                }
            }
            $customFields["address"] = $userAdresses;
        }
        return view("order.make")->with([
            "market" => $market,
            "customFields" => $customFields
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'address' => 'string|max:255',
            'order' => 'required',
            'payment_method' => 'required|string',
            'orderType' => 'required',
            'phone_type' => 'required',
        ]);
        try {
            $response = $this->makeOrder($request);
            
            if ($response[0] instanceof Market && $response[1] instanceof Order) {
                if ($request->payment_method == "card") {
                    $this->credit($response[1]);
                }
                return redirect("/order/confirm")->with('market', $response[0])->with('order', $response[1]);
            }
            return redirect("/order/not-confirm")->with('message', "Order Not confirmed");
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            return redirect('/order/not-confirm')->with('message', "Order Not confirmed");
        }
    }
    public function confirm()
    {
        if (session('market') && session('order')) {
            return view('order.confirm')->with([
                'market' => session('market'),
                'order' => session('order')
            ]);
        }
        abort(404);
    }
    public function notConfirm()
    {
        if (session('message')) {
            return view('order.not_confirm');
        }
        abort(404);
    }
    private function makeOrder($request)
    {
        $lat = $_COOKIE['lat'];
        $lon = $_COOKIE['lng'];
        
        $order = collect(json_decode($request->order, true));
        
        $user = auth()->user();
        $market = Market::findOrFail($order->get('market')['id']);

        if ($market->closed) {
            return;
        }

        if (!empty($_COOKIE['lat'])) {
            $distanceMarket = Market::select(DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians(" . $lon . "))
                + sin(radians(" . $lat . "))
                * sin(radians(latitude))) AS distance"))->where('id', $market->id)->first();
            // dd($market->delivery_range);
            // if ($distanceMarket->distance > 3)
            //     return view('order.not_confirm');
            // dd($distanceMarket->distance ."***********************". $market->delivery_range);
            if ($distanceMarket->distance > $market->delivery_range){
                return view('order.not_confirm');
            }
        }
        if (empty($_COOKIE['lat'])){
            return view('order.not_confirm');
        }


        $totalPrice = 0;
        $orders = array();
        foreach ($order->get('orders') as $theOrder) {
            $productOrdered = Product::findOrFail($theOrder['product_id']);
            $productOrderedOptions = array();
            $orderPrice = $productOrdered->getPrice();
            foreach ($theOrder['options'] as $option_id) {
                $option = Option::findOrFail($option_id);
                $orderPrice = $orderPrice + $option->price;
                array_push($productOrderedOptions, $option);
            }
            $totalPrice = $totalPrice + ($orderPrice * $theOrder['numberOfMeals']);
            array_push($orders, [
                'product' => $productOrdered,
                'productOptions' => $productOrderedOptions,
                'price' => $orderPrice * $theOrder['numberOfMeals'],
                'numberOfMeals' => $theOrder['numberOfMeals'],
            ]);
        }
        
        if ($order->get('orderType') == 'Delivery') {
            $totalPrice = $totalPrice + $market->delivery_fee;
        }
        if (request('coupon')) {
            $coupon = Coupon::where('code', request('coupon'))->first();
            if ($coupon != null) {
                $couponData = $coupon->checkValidation();
                if ($couponData['valid']) {
                    foreach ($orders as $order) {
                        if (in_array($order["product"]->id, $couponData['discountables']["products"])) {
                            $dis = $order["product"]->getPrice() - ($order["product"]->getPrice() - ($order["product"]->getPrice() * $couponData["discount"]) / 100);
                        } elseif (in_array($order["product"]->restaurant_id, $couponData['discountables']["products"])) {
                            $dis = $order["product"]->getPrice() - ($order["product"]->getPrice() - ($order["product"]->getPrice() * $couponData["discount"]) / 100);
                        } elseif (in_array($order["product"]->category_id, $couponData['discountables']["categorys"])) {
                            $dis = $order["product"]->getPrice() - ($order["product"]->getPrice() - ($order["product"]->getPrice() * $couponData["discount"]) / 100);
                        } else {
                            $dis = 0;
                        }
                        
                        $totalPrice = $totalPrice - $dis * $order["numberOfMeals"];

                        if( $dis != 0){
                            $coupon['count'] = $coupon['count'] + 1;
                            $coupon->save();
                        }
                    }
                }
            }
        }
        // dd('order ready to go');
        // $tax = $market->default_tax;
        $tax = $market->admin_commission;
        $totalPrice = $totalPrice + $totalPrice * $tax / 100;

        $payment = Payment::create([
            "price" => $totalPrice,
            "user_id" => $user->id,
            "description" => "Order not paid yet",
            "status" => "Waiting for Client",
            "method" => $request->payment_method,
        ]);
        if ($request->orderType == "Delivery") {
            $deliveryAddresse = DeliveryAddresse::create([
                "description" => $request->delivery_address_description ? $request->delivery_address_description : "default user address",
                "address" => $request->address ? $request->address : null,
                "is_default" => $request->address_type == "default" ? true : false,
                "user_id" => $user->id,
            ]);
        }
        if ($request->address_type != "default" && $request->address != "" && $request->address != null) {
            CustomFieldValue::create([
                "value" => $request->address,
                "view" => $request->address,
                "custom_field_id" => 6,
                "customizable_type" => "App\Models\User",
                "customizable_id" => $user->id
            ]);
        }
        if ($request->phone_type != "default") {
            CustomFieldValue::create([
                "value" => $request->phone,
                "view" => $request->phone,
                "custom_field_id" => 4,
                "customizable_type" => "App\Models\User",
                "customizable_id" => $user->id
            ]);
        }
        $order = Order::create([
            'user_id' => $user->id,
            'order_status_id' => $request->payment_method === "card" ? 6 : 2,//status id 6 refer to 'pending' order  and 2 for 'Preparing' order
            'tax' => $market->admin_commission,
            'delivery_fee' => $market->delivery_fee,
            'delivery_address_id' => $request->orderType == "Delivery" ? $deliveryAddresse->id : null,
            'payment_id' => $payment->id,
            'hint' => $request->hint ? $request->hint : null
        ]);
        foreach ($orders as $theOrder) {
            $productOrder = productOrder::create([
                "product_id" => $theOrder["product"]->id,
                "order_id" => $order->id,
                "quantity" => $theOrder["numberOfMeals"],
                "price" => $theOrder["product"]->price,
            ]);
            // $cart = Cart::create([
            //     'product_id' => $theOrder["product"]->id,
            //     'user_id' => $user->id,
            //     'quantity' => $theOrder["numberOfMeals"]
            // ]);
            foreach ($theOrder['productOptions'] as $productOptions) {
                ProductOrderOption::create([
                    "product_order_id" => $productOrder->id,
                    "option_id" => $productOptions->id,
                    "price" => $productOptions->price,
                ]);
                // CartOption::create([
                //     'option_id' => $productOptions->id,
                //     'cart_id' => $cart->id
                // ]);
            }
        }

        // Notification::send($order->productOrders[0]->product->market->users, new NewOrder($order));

        return [$market, $order];
    }


    //start paymob getway functions 
    public function credit($order)
    {

        $db_order = Order::where('id', $order->id)->first();

        $token = $this->getToken();
        $order = $this->createOrder($db_order, $token);
        // dd($order);
        $paymentToken = $this->getPaymentToken($order, $db_order, $token);
        header('Location: https://portal.weaccept.co/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken);
        die();
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
            // delete user cart and cart options
            Cart::where('user_id', $db_order->user_id)->delete();

            return redirect("/order/confirm")->with('market', $db_order->productOrders[0]->product->market)->with('order', $db_order);
            exit;
        }
        $db_order->update(['order_status_id' => 7]); // status 'Canceled'
        Payment::where('id', $db_order->payment_id)->update(['description' => 'Order not Payed', 'status' => 'Not paid']);
        // delete user cart and cart options
        Cart::where('user_id', $db_order->user_id)->delete();
        
        return redirect("/order/not-confirm")->with('message', "Order Not confirmed");
        exit;
    }
    //end paymob getway functions 


}

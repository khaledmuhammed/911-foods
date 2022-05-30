<?php

namespace App\Http\Controllers\api;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Market;
use App\Models\Option;
use App\Models\Payment;
use App\Models\Product;
use App\Models\CartOption;
use App\Models\productOrder;
use Illuminate\Http\Request;
use App\Libraries\ApiResponse;
use App\Libraries\ApiValidator;
use App\Models\CustomFieldValue;
use App\Models\DeliveryAddresse;
use App\Models\ProductOrderOption;
use Illuminate\Support\Facades\DB;
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

    public function search(Request $request)
    {
        // dd('hi');
        return response()->json([
            "markets" => Market::where('name', 'like', '%' . $request->search . '%')
                ->take(10)
                ->get()
                ->map
                ->format()
        ]);
    }

    public function paymob_checkout(Request $request)
    {
        // dd($request['delivery_address_id']); طالع ب 40
        if (empty($request['api_token'])) {
            return response('User Token not found', 404)->header('Content-Type', 'text/plain');
        }
        if (empty($request['delivery_address_id'])) {
            return response('delivery Address not found', 404)->header('Content-Type', 'text/plain');
        }

        $user = User::where('api_token', $request['api_token'])->firstOrFail();
        // dd($user);
        if (!$user) {
            return response('User not found', 404)->header('Content-Type', 'text/plain');
        }
        if (empty($user->cart[0])) {
            return response('User cart is empty Please add items to your cart', 404)->header('Content-Type', 'text/plain');
        }

        $request->orders = [];
        for ($i = 0; $i < count($user->cart); $i++) {

            $request->orders[$i]['product_id'] =  $user->cart[$i]->product->id;
            $request->orders[$i]['product_name'] = $user->cart[$i]->product->name;
            $request->orders[$i]['options'] = $user->cart[$i]->options;
            $request->orders[$i]['numberOfMeals'] = $user->cart[$i]->quantity;
            $request->orders[$i]['productCategory'] = $user->cart[$i]->product->category_id;
            $request->orders[$i]['productMarket'] = $user->cart[$i]->product->market_id;
            $request->orders[$i]['price'] = $user->cart[$i]->product->price;
        }
        $request->orderType = "Delivery";
        $request->phone_type = "default";
        $request->delivery_fee = $user->cart[0]->product->market->delivery_fee;
        $request->payment_method = "card";
        $request->user_id = $user->id;

        try {

            $response = $this->makeOrder($request); //$response[1] is the order object
            $credit_link = $this->credit($response[1]);

            return redirect()->away('https://portal.weaccept.co/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $credit_link);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
    }

    private function makeOrder($request)
    {
        $request_orders = $request->orders;
        $user = User::where('id', $request->user_id)->first();
        $market = Market::findOrFail($request->orders[0]['productMarket']);

        if ($market->closed) {
            return;
        }

        $totalPrice = 0;
        $orders = array();
        foreach ($request_orders as $theOrder) {

            $productOrdered = Product::findOrFail($theOrder['product_id']);
            $productOrderedOptions = array();
            $orderPrice = $productOrdered->getPrice();
            foreach ($theOrder['options'] as $option_id) {
                $option = Option::findOrFail($option_id->id);
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

        if ($request->orderType == 'Delivery') {
            $totalPrice = $totalPrice + $market->delivery_fee;
        }
        if ($request['coupon_code']) {
            $coupon = Coupon::where('code', $request['coupon_code'])->first();
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
        // if ($request->address_type != "default" && $request->address != "" && $request->address != null) {
        //     CustomFieldValue::create([
        //         "value" => $request->address,
        //         "view" => $request->address,
        //         "custom_field_id" => 6,
        //         "customizable_type" => "App\Models\User",
        //         "customizable_id" => $user->id
        //     ]);
        // }

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
            'order_status_id' => $request->payment_method === "card" ? 6 : 2, //status id 6 refer to 'pending' order  and 2 for 'Preparing' order
            'tax' => $market->admin_commission,
            'delivery_fee' => $market->delivery_fee,
            'delivery_address_id' => $request->orderType == "Delivery" ? $request->delivery_address_id : null,
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
        //dd( Notification::send($order->productOrders[0]->product->market->users, new NewOrder($order))$order;

        return [$market, $order];
    }




    //start paymob getway functions 
    public function credit($db_order)
    {

        $token = $this->getToken();
        $order = $this->createOrder($db_order, $token);
        $paymentToken = $this->getPaymentToken($order, $db_order, $token);

        return $paymentToken;
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
        // dd($response->object());
        return $response->object();
    }


    //end paymob getway functions 


}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    function check()
    {
        $coupon = Coupon::where('code', request('coupon'))->first();
        if (!$coupon) {
            $data = ["valid" => false];
        } else {
            $data = $coupon->checkValidation();
        }
        return response()->json($data);
    }
}

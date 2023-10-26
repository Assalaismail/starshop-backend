<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\orderAddresses;
use App\Models\couponCodes;
use App\Models\orders;
use App\Models\payments;
use Faker\Provider\ar_EG\Payment;

class OrdersController extends ApiController
{
    // coupon code
    protected function calculateDiscount($couponCode, $totalAmount)
    {
        $discount = 0;

        $discountInfo = couponCodes::where('code', $couponCode)
            ->where('end_date', '>=', now()) // Check for not expired coupons
            ->first();

        // dd($discountInfo);

        if ($discountInfo) {
            if ($discountInfo->type_option === 'percentage') {
                $discount = ($discountInfo->value / 100) * $totalAmount;
            }
        }
        return $discount;
    }

    //checkout
    public function checkout(Request $request)
    {

        $request->validate([
            //order
            'user_id' => 'required',
            // 'payment_id' => 'required|exists:payments,id',
            'status' => 'nullable|in:pending,processing,completed,canceled',
            'coupon_code' => 'string|max:255',

            //order_product
            // 'products' => 'required|array',
            // 'products.*.product_id' => 'required|exists:products,id',
            // 'products.*.quantity' => 'required|integer|min:1',

            //payments
            'currency' => 'required',
            'payment_channel' => 'required',
            // 'status' => 'nullable|in:pending,processing,completed,canceled',

            //address
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);

        // Calculate the total order amount
        $totalAmount = 0;
        $totalWeight = 0;
        $amount = 0;
        $discount = 0;

        $user_id = $request->input('user_id');

        // Apply discount if coupon code is provided
        $couponCode = $request->input('coupon_code');
        if ($couponCode) {
            $discount = $this->calculateDiscount($couponCode, $totalAmount);
            $amount = $totalAmount - $discount;
        } else {
            $amount = $totalAmount;
        }

        $addresses = new orderAddresses();

        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $country = $request->input('country');
        $state = $request->input('state');
        $city = $request->input('city');
        $address = $request->input('address');

        $addresses->name = $name;
        $addresses->email = $email;
        $addresses->phone = $phone;
        $addresses->country = $country;
        $addresses->state = $state;
        $addresses->city = $city;
        $addresses->address = $address;
        // $addresses->save();

        // Generate a random charge_id
        $chargeId = uniqid();

        // Create the order
        $order = new Orders();
        $order->user_id = $user_id;
        $order->total = $totalAmount;
        $order->amount = $amount; // Set the total amount
        $order->coupon_code = $couponCode;
        $order->discount_amount = $discount;
        $order->status = $request->input('status', 'pending');

        // Generate a random charge_id
        $chargeId = uniqid();
        //payments
        $payment = new Payments();
        $payment->user_id = $order->user_id;
        $payment->amount = $order->amount;
        $payment->currency = $request->input('currency');
        $payment->payment_channel = $request->input('payment_channel');
        $payment->charge_id = $chargeId;
        $payment->save();

        $order->payment_id = $payment->id;
        $order->save();

        $payment->order_id = $order->id;
        $payment->save();




        $addresses->order_id = $order->id;
        $addresses->save();

        return response()->json([
            'message' => 'DONE! Order Created Successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\orderAddresses;
use App\Models\couponCodes;
use App\Models\orders;
use App\Models\payments;
use App\Models\products;
use App\Models\OrderProduct;


class OrdersController extends ApiController
{
    // coupon code
    protected function calculateDiscount($couponCode, $amount)
    {
        $discount = 0;

        $discountInfo = couponCodes::where('code', $couponCode)
            ->where('end_date', '>=', now()) // Check for not expired coupons
            ->first();


        if ($discountInfo) {
            if ($discountInfo->type_option === 'percentage') {
                $discount = ($discountInfo->value / 100) * $amount;
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
            'status' => 'nullable|in:pending,processing,completed,canceled',
            'coupon_code' => 'sometimes|string|max:255',

            //order_product
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',

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

        // Address
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

        // Create the order
        $order = new Orders();
        $order->user_id = $user_id;
        $order->total = $totalAmount;
        $order->coupon_code = $couponCode;
        $order->discount_amount = $discount;
        $order->status = $request->input('status', 'pending');

         // Attach products to the order
         foreach ($request->input('products') as $productData) {
            $product = Products::find($productData['product_id']);
            if ($product) {

                $quantity = $productData['quantity'];
                $subtotal = $product->price * $quantity;
                $amount += $subtotal; // Add the subtotal to the total amount

                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $productData['quantity'],
                ]);
            // Update the quantity of the product in the products table
            $product->decrement('quantity', $productData['quantity']);
        } else {
            // Handle insufficient quantity error, e.g., return an error response
            return response()->json(['message' => 'Insufficient quantity available for ' . $product->name], 400);
        }
        }

         // Apply discount if coupon code is provided
         $couponCode = $request->input('coupon_code');
         if ($couponCode) {
             $discount = $this->calculateDiscount($couponCode, $amount);
             $total = $amount - $discount;
         } else {
             $total = $amount;
         }

        // payments
        // Generate a random charge_id
        $chargeId = uniqid();

        $payment = new Payments();
        $payment->user_id = $order->user_id;
        $payment->currency = $request->input('currency');
        $payment->payment_channel = $request->input('payment_channel');
        $payment->charge_id = $chargeId;
        $payment->save();

        $order->payment_id = $payment->id;
        $order->amount = $amount;
        $order->discount_amount= $discount;
        $order->total=$total;
        $order->save();

        $payment->order_id = $order->id;
        $payment->amount = $order->total;
        $payment->save();


        $addresses->order_id = $order->id;
        $addresses->save();

        return response()->json([
            'message' => 'DONE! Order Created Successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\orderAddresses;


class orderAddressesController extends ApiController
{
    //get all order address
    public function getAllOrderAddress(Request $request)
    {
       $addresses = orderAddresses::all();
       $collection = [];
       foreach ($addresses as $address) {
           $collection[] = [
               'id' => $address->id,
               'name' => $address->name,
               'email' => $address->email,
               'phone' => $address->phone,
               'country' => $address->country,
               'state' => $address->state,
               'city' => $address->city,
               'address' => $address->address,
               'order_id' => $address->order_id,
           ];
       }
       return $this->apiResponse($addresses, self::STATUS_OK, __('Response ok!'));
    }

     //add new orderAddress
     public function orderAddress(Request $request)
     {
         $addresses = new orderAddresses;

         $request->validate([
             'name' => 'required',
             'email' => 'required',
             'phone' => 'required',
             'country' => 'required',
             'state' => 'required',
             'city' => 'required',
             'address' => 'required',
             'order_id' => 'required',
         ]);

         $name = $request->input('name');
         $email = $request->input('email');
         $phone = $request->input('phone');
         $country = $request->input('country');
         $state = $request->input('state');
         $city = $request->input('city');
         $address = $request->input('address');
         $order_id = $request->input('order_id');


         $addresses->name = $name;
         $addresses->email = $email;
         $addresses->phone = $phone;
         $addresses->country = $country;
         $addresses->state = $state;
         $addresses->city = $city;
         $addresses->address = $address;
         $addresses->order_id = $order_id;
         $addresses->save();

         return response()->json([
             'message' => 'DONE! Address Created Successfully',
             'Addresses' => $addresses,
         ]);
     }

}

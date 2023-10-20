<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\countries;



class CountriesController extends ApiController
{
    //get all countries
    public function getAllCountries(Request $request)
    {
       $countries = countries::all();
       $collection = [];
       foreach ($countries as $country) {
           $collection[] = [
               'id' => $country->id,
               'sortname' => $country->sortname,
               'name' => $country->name,
               'phonecode' => $country->phonecode,

           ];
       }
       return $this->apiResponse($countries, self::STATUS_OK, __('Response ok!'));
    }


     //add new Country
     public function addCountry(Request $request)
     {
         $countries = new countries;

         $request->validate([
             'sortname' => 'required',
             'name' => 'required',
             'phonecode' => 'required',
         ]);

         $sortname = $request->input('sortname');
         $name = $request->input('name');
         $phonecode = $request->input('phonecode');


         $countries->sortname = $sortname;
         $countries->name = $name;
         $countries->phonecode = $phonecode;
         $countries->save();

         return response()->json([
             'message' => 'DONE! Country Created Successfully',
             'Countries' => $countries,
         ]);
     }

}

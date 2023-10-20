<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\states;
use App\Models\countries;


class StatesController extends ApiController
{
    //get all states
    public function getAllStates(Request $request)
    {
        $states = states::all();
        $collection = [];
        foreach ($states as $state) {
            $collection[] = [
                'id' => $state->id,
                'name' => $state->slug,
                'country_id' => $state->country_id,

            ];
        }
        return $this->apiResponse($states, self::STATUS_OK, __('Response ok!'));
    }



    //add new State
    public function addState(Request $request)
    {
        $states = new states();

        $request->validate([
            'name' => 'required',
            'country_id' => 'required',
        ]);


        $name = $request->input('name');
        $country_id = $request->input('country_id');



        $states->name = $name;
        $states->country_id = $country_id;
        $states->save();

        return response()->json([
            'message' => 'DONE! Country Created Successfully',
            'States' => $states,
        ]);
    }


            //get by country_id
            public function getStateByCountryId($country_id)
            {
            $countries = countries::find($country_id);

            if (!$countries) {
                return $this->apiResponse(null, self::STATUS_NOT_FOUND, __('Country not found.'));
            }

            $state = states::where('country_id', $country_id)
            ->get();

            return $this->apiResponse($state, self::STATUS_OK, __('Response ok!'));
            }



}

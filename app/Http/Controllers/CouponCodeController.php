<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\couponCodes;

class CouponCodeController extends ApiController
{
    //get all coupon codes
    public function getAllCodes(Request $request)
    {
        $codes = couponCodes::all();
        $collection = [];
        foreach ($codes as $code) {
            $collection[] = [
                'id' => $code->id,
                'title' => $code->title,
                'code' => $code->code,
                'value' => $code->value,
                'type' => $code->type,
                'type_option' => $code->type_option,
            ];
        }
        return $this->apiResponse($codes, self::STATUS_OK, __('Response ok!'));
    }

    //add new Coupon code
    public function addCouponCode(Request $request)
    {
        $couponCodes = new couponCodes();

        $request->validate([
            'title' => 'required',
            'code' => 'required',
            'value' => 'required',
            'type' => 'required',
            'type_option' => 'required',
        ]);

        $title = $request->input('title');
        $code = $request->input('code');
        $value = $request->input('value');
        $type = $request->input('type');
        $type_option = $request->input('type_option');

        $couponCodes->title = $title;
        $couponCodes->code = $code;
        $couponCodes->value = $value;
        $couponCodes->type = $type;
        $couponCodes->type_option = $type_option;
        $couponCodes->save();

        return response()->json([
            'message' => 'DONE! Code Created Successfully',
            'couponCodes' => $couponCodes,
        ]);
    }

    //delete CouponCode
    public function deleteCouponCode(Request $request, $id)
    {
        $code = couponCodes::find($id);
        if (!$code) {
            return response()->json(['message' => 'Code not found.'], 404);
        }
        $code->delete();

        return response()->json([
            'message' => 'DONE! Code deleted',
        ]);
    }
}

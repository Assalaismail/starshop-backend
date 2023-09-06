<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Attributes;
use App\Models\setattributes;



class AttributesController extends ApiController
{
      //get all Attributes
      public function getAllAttributes(Request $request)
      {
         $Attributes = Attributes::all();
         $collection = [];
         foreach ($Attributes as $Attribute) {
             $collection[] = [
                 'id' => $Attribute->id,
                 'title' => $Attribute->name,
                 'slug' => $Attribute->slug,
                 'abbreviation' => $Attribute->abbreviation,
                 'status' => $Attribute->status,
                 'color' => $Attribute->color,
                 'attribute_set_id' => $Attribute->attribute_set_id,
             ];
         }
         return $this->apiResponse($collection, self::STATUS_OK, __('Response ok!'));
      }


          //get by setattributes_id
    public function getAttributeBySetAttributeId($setattributes_id)
    {
    $setattributes = setattributes::find($setattributes_id);

    if (!$setattributes) {
        return $this->apiResponse(null, self::STATUS_NOT_FOUND, __('Attributes not found.'));
    }

    $attributes = Attributes::where('attribute_set_id', $setattributes_id)
    ->where('status', 'published')
    ->get();

    return $this->apiResponse($attributes, self::STATUS_OK, __('Response ok!'));
    }


       //add new  Attributes
    public function addAttributes(Request $request)
    {
        $attributes = new Attributes;

        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'abbreviation' => 'required',
            'status' => 'required',
            'attribute_set_id' => 'required'
        ]);

        $title = $request->input('title');
        $slug = $request->input('slug');
        $abbreviation = $request->input('abbreviation');
        $status = $request->input('status');
        $color = $request->input('color');
        $attribute_set_id = $request->input('attribute_set_id');


        $attributes->title = $title;
        $attributes->slug = $slug;
        $attributes->abbreviation = $abbreviation;
        $attributes->status = $status;
        $attributes->color = $color;
        $attributes->attribute_set_id = $attribute_set_id;
        $attributes->save();

        return response()->json([
            'message' => 'DONE! SetAttributes Created Successfully',
            'Set Attributes' => $attributes,
        ]);
    }

      //update Attributes
      public function updateAttributes(Request $request, $id)
      {
          $attributes = Attributes::find($id);
          $attributes->fill($request->only([
              'title',
              'slug',
              'abbreviation',
              'status',
              'attribute_set_id',
          ]));

          $attributes->save();

          return response()->json([
              'message' => 'DONE! Attributes updated',
          ]);
      }
}

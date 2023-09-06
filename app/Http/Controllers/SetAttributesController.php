<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\setattributes;

class SetAttributesController extends ApiController
{
     //get all SetAttributes
     public function getAllSetAttributes(Request $request)
     {
        $setAttributes = setattributes::all();
        $collection = [];
        foreach ($setAttributes as $setAttribute) {
            $collection[] = [
                'id' => $setAttribute->id,
                'title' => $setAttribute->name,
                'slug' => $setAttribute->slug,
                'status' => $setAttribute->status,
            ];
        }
        return $this->apiResponse($collection, self::STATUS_OK, __('Response ok!'));
     }


     //add new Set Attributes
    public function addSetAttributes(Request $request)
    {
        $setattributes = new setattributes;

        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'status' => 'required',
        ]);

        $title = $request->input('title');
        $slug = $request->input('slug');
        $status = $request->input('status');


        $setattributes->title = $title;
        $setattributes->slug = $slug;
        $setattributes->status = $status;
        $setattributes->save();

        return response()->json([
            'message' => 'DONE! SetAttributes Created Successfully',
            'Set Attributes' => $setattributes,
        ]);
    }

      //update setattributes
      public function updateSetAttributes(Request $request, $id)
      {
          $set = setattributes::find($id);
          $set->fill($request->only([
              'title',
              'slug',
              'status',
          ]));

          $set->save();

          return response()->json([
              'message' => 'DONE! SetAttributes updated',
          ]);
      }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\categories;
use App\Models\subcategory;



class SubcategoryController extends ApiController
{
      //get all subcategories
      public function getAllSubCategories(Request $request)
      {
         $categories = subcategory::all();
         $collection = [];
         foreach ($categories as $category) {
             $collection[] = [
                 'id' => $category->id,
                 'name' => $category->name,
                 'slug' => $category->slug,
                 'abbreviation' => $category->abbreviation,
                 'status' => $category->status,
                 'category_id' => $category->category_id,
             ];
         }
         return $this->apiResponse($collection, self::STATUS_OK, __('Response ok!'));
      }


      //get by category_id
    public function getSubByCategoryId($category_id)
    {
    $category = categories::find($category_id);

    if (!$category) {
        return $this->apiResponse(null, self::STATUS_NOT_FOUND, __('Category not found.'));
    }

    $subcategories = Subcategory::where('category_id', $category_id)->get();

    return $this->apiResponse($subcategories, self::STATUS_OK, __('Response ok!'));
    }

       //get by category's name
    public function getSubByCategoryName($categoryName)
    {
    // Find the category by its name
    $category = categories::where('name', $categoryName)->first();

    if (!$category) {
        return $this->apiResponse(null, self::STATUS_NOT_FOUND, __('Category not found.'));
    }
    // Retrieve subcategories associated with the found category
    $subcategories = Subcategory::where('category_id', $category->id)->get();

    return $this->apiResponse($subcategories, self::STATUS_OK, __('Response ok!'));
   }



       //add new category
    public function addSubCategory(Request $request)
    {
        $category = new subcategory;

        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'abbreviation' => 'required',
            'status' => 'required',
            'category_id' => 'required',
        ]);

        $name = $request->input('name');
        $slug = $request->input('slug');
        $abbreviation = $request->input('abbreviation');
        $status = $request->input('status');
        $category_id = $request->input('category_id');


        $category->name = $name;
        $category->slug = $slug;
        $category->abbreviation = $abbreviation;
        $category->status = $status;
        $category->category_id = $category_id;
        $category->save();

        return response()->json([
            'message' => 'DONE! SubCategory Created Successfully',
        ]);
    }



}

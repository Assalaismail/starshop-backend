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

    $subcategories = Subcategory::where('category_id', $category_id)
    ->where('status', 'published')
    ->get();

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
    $subcategories = Subcategory::where('category_id', $category->id)
    ->where('status', 'published')
    ->get();

    return $this->apiResponse($subcategories, self::STATUS_OK, __('Response ok!'));
   }


   public function getSubCategoriesToTheHomePage()
   {
       $productNames = ['Dress', 'Top', 'Jumpsuit', 'Set', 'Skirt', 'Pants'];

       $products = Subcategory::whereIn('name', $productNames)->get();

       return $this->apiResponse($products, self::STATUS_OK, __('Response ok!'));

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
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image

        ]);

        if ($request->hasFile('image')) {
            // Get the uploaded image from the request
        $image = $request->file('image');

        // Generate a unique name for the image
        $imageName = $image->getClientOriginalName();

        // Upload the image to the storage folder (public disk)
        $imagePath = $image->storeAs('public/subcategories', $imageName);

        // Generate the URL for the image
        $imageUrl = asset('storage/subcategories/' . $imageName);
        $category->image = $imageUrl; // Store the image URL, not the path

     }

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
            'SubCategory' => $category,
        ]);
    }


      //delete subCategory
      public function deleteSubCategory(Request $request, $id){
        $sub = subcategory::find($id);
        if (!$sub) {
            return response()->json(['message' => 'SubCategory not found.'], 404);
        }
        $sub->delete();

        return response()->json([
            'message' => 'DONE! SubCategory deleted'
        ]);
    }

        //update subCategory
        public function updateSubCategory(Request $request, $id)
        {
            $sub = subcategory::find($id);
            $sub->fill($request->only([
                'name',
                'slug',
                'abbreviation',
                'status',
                'category_id',
            ]));

            $sub->save();

            return response()->json([
                'message' => 'DONE! SubCategory updated',
            ]);
        }



}

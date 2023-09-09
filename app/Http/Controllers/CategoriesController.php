<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;
use App\Http\Controllers\ApiController;


class CategoriesController extends ApiController
{
     //get all categories
     public function getAllCategories(Request $request)
     {
        $categories = categories::all();
        $collection = [];
        foreach ($categories as $category) {
            $collection[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'image' => $category->image,
            ];
        }
        return $this->apiResponse($collection, self::STATUS_OK, __('Response ok!'));
     }

    //get category by ID
    public function getCategoryById(Request $request, $id)
    {
        $category = categories::find($id);
        return response()->json([
            'message' => $category,
        ]);
    }

    //add new category
    public function addCategory(Request $request)
    {
        $category = new categories;

        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $name = $request->input('name');
        $slug = $request->input('slug');


 if ($request->hasFile('image')) {
        // Get the uploaded image from the request
    $image = $request->file('image');

    // Generate a unique name for the image
    $imageName = $image->getClientOriginalName();

    // Upload the image to the storage folder (public disk)
    $imagePath = $image->storeAs('public/categories', $imageName);

    // Generate the URL for the image
    $imageUrl = asset('storage/categories/' . $imageName);
    $category->image = $imageUrl; // Store the image URL, not the path

 }

        $category->name = $name;
        $category->slug = $slug;
        // $category->image = $imageUrl; // Store the image URL, not the path
        $category->save();

        return response()->json([
            'message' => 'DONE! Category Created Successfully',
        ]);
    }

}

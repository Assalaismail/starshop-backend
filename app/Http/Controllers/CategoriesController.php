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
            // 'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $name = $request->input('name');
        $slug = $request->input('slug');


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('category_images'), $imageName);
            $category->image_url = asset('category_images/' . $imageName); // Store the image URL
        }

        $category->name = $name;
        $category->slug = $slug;
        $category->save();

        return response()->json([
            'message' => 'DONE! Category Created Successfully',
        ]);
    }

}

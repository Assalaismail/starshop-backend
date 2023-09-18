<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\products;


class ProductsController extends ApiController
{
     //get all products
    public function getAllProducts(Request $request)
    {
       $categories = products::all();
       $collection = [];
       foreach ($categories as $category) {
           $collection[] = [
               'id' => $category->id,
               'name' => $category->name,
               'slug' => $category->slug,
               'status' => $category->status,
               'category_id' => $category->category_id,
           ];
       }
       return $this->apiResponse($categories, self::STATUS_OK, __('Response ok!'));
    }


    //add new products
    public function addProducts(Request $request)
    {

       $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'status' => 'required|string',
        'category_id' => 'required|numeric|min:0.01',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif',
      ]);

      // Upload and store the images
       $imagePaths = [];

       if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imageName = $image->getClientOriginalName();

            $imagePath = $image->storeAs('public/products', $imageName);

           $imageUrl = asset('storage/products/' . $imageName);

            $imagePaths[] = $imageUrl;
        }
    }

      // Create a new product with image filenames
       $product = Products::create([
          'name' => $validatedData['name'],
          'slug' => $validatedData['slug'],
          'status' => $validatedData['status'],
          'category_id' => $validatedData['category_id'],
          'images' => json_encode($imagePaths),
          // 'images' => $imagePaths,
      ]);

     return response()->json([
                'message' => 'DONE! Product Created Successfully',
                'Product' => $product,
            ]);
    }
}

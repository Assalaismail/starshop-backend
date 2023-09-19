<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\products;
use App\Models\subcategory;



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
        'sku' => 'required|string|max:255',
        'status' => 'required|string',
        'stock_status' => 'required|string',

        'size' => 'required|array',
        'color' => 'required|array',

        'price' => 'required|numeric|min:0.01',
        'subcategory_id' => 'required|numeric|min:0.01',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif',
      ]);

       // Fetch the corresponding category_id based on the subcategory_id
    $subcategory = Subcategory::find($validatedData['subcategory_id']);

    if (!$subcategory) {
        return response()->json([
            'error' => 'Invalid subcategory_id',
        ], 400);
    }

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
          'sku' => $validatedData['sku'],
          'status' => $validatedData['status'],
          'stock_status' => $validatedData['stock_status'],

          'size' => json_encode($validatedData['size']), // Store as JSON array
          'color' => json_encode($validatedData['color']),

          'category_id' => $subcategory->category_id,
          'price' => $validatedData['price'],
          'subcategory_id' => $validatedData['subcategory_id'],
          'subcategory_abbreviation' => $subcategory->abbreviation,
          'images' => json_encode($imagePaths),
          // 'images' => $imagePaths,
      ]);

     return response()->json([
                'message' => 'DONE! Product Created Successfully',
                'Product' => $product,
            ]);
    }


    //get by product id
    public function getProductById($id)
    {
    $product = Products::findOrFail($id);

    // Decode the JSON-encoded images
    $imagePaths = json_decode($product->images, true);
    $size = json_decode($product->size, true);
    $color = json_decode($product->color, true);

    return response()->json([
        'product' => $product,
        'images' => $imagePaths, // Send the image URLs in the response
        'size' => $size,
        'color' => $color,
    ]);
   }

       //get by subcategory's name
       public function getProductBySubCategoryName($subcategoryName)
       {
       // Find the category by its name
       $subcategory = subcategory::where('name', $subcategoryName)->first();

       if (!$subcategory) {
           return $this->apiResponse(null, self::STATUS_NOT_FOUND, __('SubCategory not found.'));
       }
       // Retrieve subcategories associated with the found category
       $products = products::where('subcategory_id', $subcategory->id)
       ->where('status', 'published')
       ->get();

       return $this->apiResponse($products, self::STATUS_OK, __('Response ok!'));
      }
}

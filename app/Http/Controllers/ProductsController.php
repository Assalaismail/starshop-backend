<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\products;
use App\Models\subcategory;
use App\Models\attributes;



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
        'stock_status' => 'required|string',
        'price' => 'required|numeric|min:0.01',
        'subcategory_id' => 'required|numeric|min:0.01',

        'variations' => 'required|array',

        'variations.*.color' => 'required|string',

        'variations.*.size' => 'required|array',
        'variations.*.size.*' => 'required|string',

        'variations.*.images' => 'sometimes|array',
        'variations.*.images.*' => 'image|mimes:jpeg,png,jpg,gif',
    ]);

    // Fetch the corresponding category_id based on the subcategory_id
    $subcategory = Subcategory::find($validatedData['subcategory_id']);

    if (!$subcategory) {
        return response()->json([
            'error' => 'Invalid subcategory_id',
        ], 400);
    }

    $products = [];

    foreach ($validatedData['variations'] as $variation) {
        $color = $variation['color'];
        $sizes = $variation['size'];

        // Upload and store the images
        $imagePaths = [];

        if (isset($variation['images'])) {
            foreach ($variation['images'] as $image) {
             $imageName = $image->getClientOriginalName();

             $imagePath = $image->storeAs('public/products', $imageName);

            $imageUrl = asset('storage/products/' . $imageName);

             $imagePaths[] = $imageUrl;
         }
     }

        foreach ($sizes as $size) {
            $product = Products::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'status' => $validatedData['status'],
                'stock_status' => $validatedData['stock_status'],
                'category_id' => $subcategory->category_id,
                'price' => $validatedData['price'],
                'subcategory_id' => $validatedData['subcategory_id'],
                'subcategory_abbreviation' => $subcategory->abbreviation,
                'color' => $color,
                'size' => $size,
                'images' => json_encode($imagePaths),
            ]);

            $products[] = $product;
        }
    }

    return response()->json([
        'message' => 'DONE! Products Created Successfully',
        'Products' => $products,
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

      public function filterByPrice(Request $request)
      {

    $minPrice = $request->input('min_price', 0); // Default to 0 if not provided
    $maxPrice = $request->input('max_price', PHP_INT_MAX); // Default to the maximum possible value if not provided

    // Query the database to retrieve products within the specified price range
    $products = Products::where('price', '>=', $minPrice)
        ->where('price', '<=', $maxPrice)
        ->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found within the specified price range.']);
        }
        return $this->apiResponse($products, self::STATUS_OK, __('Response ok!'));
       }

}

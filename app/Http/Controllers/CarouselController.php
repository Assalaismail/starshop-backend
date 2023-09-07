<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\carousel;



class CarouselController extends ApiController
{

      //get all subcategories
      public function getAllCarousel(Request $request)
      {
         $carousel = Carousel::all();
         $collection = [];
         foreach ($carousel as $car) {
             $collection[] = [
                 'id' => $car->id,
                 'name' => $car->name,
                 'description' => $car->description,
                 'image' => $car->image,

             ];
         }
         return $this->apiResponse($collection, self::STATUS_OK, __('Response ok!'));
      }


    public function addImage(Request $request)
{
    $carousel = new Carousel;

    $request->validate([
        'name' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Get the uploaded image from the request
    $image = $request->file('image');

    // Generate a unique name for the image
    $imageName = $image->getClientOriginalName();

    // Upload the image to the storage folder (public disk)
    $imagePath = $image->storeAs('public/products', $imageName);

    // Generate the URL for the image
    $imageUrl = asset('storage/products/' . $imageName);

    $name = $request->input('name');
    $description = $request->input('description');

    $carousel->name = $name;
    $carousel->description = $description;
    $carousel->image = $imageUrl; // Store the image URL, not the path
    $carousel->save();

    return response()->json([
        'message' => 'DONE! Created Successfully',
    ]);
}


  //delete Carousel
  public function deleteCarousel(Request $request, $id){
    $Carousel = Carousel::find($id);
    if (!$Carousel) {
        return response()->json(['message' => 'Carousel not found.'], 404);
    }
    $Carousel->delete();

    return response()->json([
        'message' => 'DONE! Successfully deleted'
    ]);
}


public function updateImage(Request $request, $id)
{
    // Find the existing Carousel record by ID
    $carousel = Carousel::findOrFail($id);

    $request->validate([
        'name' => 'sometimes',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Update the name and description fields
    $carousel->name = $request->input('name');
    $carousel->description = $request->input('description');

    // Check if a new image was provided
    if ($request->hasFile('image')) {
        $image = $request->file('image');

        // Generate a unique name for the image
        $imageName = $image->getClientOriginalName();

        // Upload the new image to the storage folder (public disk)
        $imagePath = $image->storeAs('public/products', $imageName);

        // Generate the URL for the new image
        $imageUrl = asset('storage/products/' . $imageName);

        // Update the image field with the new URL
        $carousel->image = $imageUrl;

        // Delete the old image if needed (optional)
        // Storage::delete($carousel->image); // Uncomment this line to delete the old image
    }

    // Save the updated Carousel record
    $carousel->save();

    return response()->json([
        'message' => 'DONE! Updated Successfully',
    ]);
}


}

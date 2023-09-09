<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserswebController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SetAttributesController;
use App\Http\Controllers\AttributesController;
use App\Http\Controllers\CarouselController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

///////////        USER            ///////
Route::get('/user', [UserswebController::class, 'getAllUsers']);
Route::get('/user/{id}', [UserswebController::class, 'getUserById']);
Route::post('/user',[UserswebController::class,'addUser']);
Route::delete('/user/{id}',[UserswebController::class,'deleteUser']);

///////////  Login  ///////////
Route::post('/login', [UserswebController::class, 'login']);

///////////  Logout  ///////////
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [UserswebController::class, 'logout']);
});
///////////  Categories ///////////
Route::get('/category', [CategoriesController::class, 'getAllCategories']);
Route::get('/category/{id}', [CategoriesController::class, 'getCategoryById']);
Route::post('/category',[CategoriesController::class,'addCategory']);

///////////  SubCategories ///////////
Route::get('/subcategory', [SubcategoryController::class, 'getAllSubCategories']);
Route::get('/subcategory/{category_id}', [SubcategoryController::class, 'getSubByCategoryId']);
Route::get('/subcategoryname/{categoryName}', [SubcategoryController::class, 'getSubByCategoryName']);
Route::get('/subcategories', [SubcategoryController::class, 'getSubCategoriesToTheHomePage']);
Route::post('/subcategory', [SubcategoryController::class, 'addSubCategory']);
Route::delete('/subcategory/{id}', [SubcategoryController::class, 'deleteSubCategory']);
Route::put('/subcategory/{id}', [SubcategoryController::class, 'updateSubCategory']);

///////////  SetAttributes ///////////
Route::get('/set', [SetAttributesController::class, 'getAllSetAttributes']);
Route::post('/set', [SetAttributesController::class, 'addSetAttributes']);
Route::put('/set/{id}', [SetAttributesController::class, 'updateSetAttributes']);

///////////  Attributes ///////////
Route::get('/attribute', [AttributesController::class, 'getAllAttributes']);
Route::get('/attribute/{setattributes_id}', [AttributesController::class, 'getAttributeBySetAttributeId']);
Route::post('/attribute', [AttributesController::class, 'addAttributes']);
Route::put('/attribute', [AttributesController::class, 'updateAttributes']);


///////////  Carousel ///////////
Route::get('/carousel', [CarouselController::class, 'getAllCarousel']);
Route::post('/carousel', [CarouselController::class, 'addImage']);
Route::put('/carousel/{id}', [CarouselController::class, 'updateImage']);
Route::delete('/carousel/{id}', [CarouselController::class, 'deleteCarousel']);

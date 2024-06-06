<?php

use App\Http\Controllers\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\RecommendedController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'verifyLogin']);

//restaurant
// Route::get('/restaurants', [RestaurantController::class, 'getRestaurant']);
Route::get('/restaurant/{id}', [RestaurantController::class, 'getRestaurantbyId']);
Route::get('/restaurants/search/name', [RestaurantController::class, 'getRestaurantsSearchByName']);
Route::get('/restaurants/map', [RestaurantController::class, 'getRestaurantForMap']);


//recommended
Route::get('/recommended', [RecommendedController::class, 'getRecommended']);

//view
Route::post('/view/insert', [ViewsController::class, 'insertView']);

Route::get('/reviews/{id}', [ReviewController::class, 'getReviewByrestaurant']);
// Route::get('/test', [ReviewController::class, 'aa']);

//apiImages
Route::get('/img', [Controller::class, 'sentimg']);
Route::get('/public/restaurants/{file_name}', function ($filename) {
    $path = storage_path('app/public/restaurants/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

//api image reviews 
Route::get('/img_reviews', [Controller::class, 'sentimgReview']);
Route::get('/public/reviews/{file_name}', function ($filename) {
    $path = storage_path('app/public/reviews/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

//catgory
Route::post('/category/insert', [Categories::class, 'insertCategory']);
Route::get('/categories', [Categories::class, 'getCategory']);


Route::get('/favorites/checkFavorites/{userId}/{restaurantId}', [FavoritesController::class, 'checkFavorite']);

Route::middleware('auth:sanctum')->group(function () {
    //user
    Route::get('/users', [UserController::class, 'getUser']);
    Route::get('/user/{id}', [UserController::class, 'getUserbyID']);
    Route::post('/user/edit/name', [UserController::class, 'updateName']);
    Route::post('/user/edit/email', [UserController::class, 'updateEmail']);
    Route::delete('/user/delete/{user_id}', [UserController::class, 'deleteAccount']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/reports/{userId}', [ReportsController::class, 'getReportByuserId']);
    Route::post('/user/changePassword/{userId}', [UserController::class, 'changePassword']);


    //favorites
    Route::post('/favorites/insert', [FavoritesController::class, 'insertFavorites']);
    Route::delete('/favorites/delete/{favorites_id}', [FavoritesController::class, 'deleteFavorites']);
    Route::get('/favorites/my/{favorites_id}', [FavoritesController::class, 'getMyFavorites']);


    //review
    Route::post('/review/insert', [ReviewController::class, 'insertReview']);
    Route::post('/review/insert/insertImages/{review_id}', [ReviewController::class, 'insertReviewImage']);

    Route::post('/review/update', [ReviewController::class, 'updateReview']);
    Route::delete('/review/delete/{review_id}', [ReviewController::class, 'deleteReview']);

    //report
    Route::post('/report/insert', [ReportsController::class, 'insertReport']);

    //restaurants
    //insert restaurant
    Route::post('/restaurant/insert', [RestaurantController::class, 'createRestaurant']);
    Route::post('/restaurant/insertCategories/{restaurantId}', [RestaurantController::class, 'insertCategories']);
    Route::post('/restaurant/insertOpenings/{restaurantId}', [RestaurantController::class, 'insertOpenings']);
    Route::post('/restaurant/insertImages/{restaurantId}', [RestaurantController::class, 'insertImages']);
    Route::delete('/restaurant/delete/{restaurant_id}', [RestaurantController::class, 'deleteRestaurant']);
    Route::get('/restaurant/myrestaurant/{userId}', [RestaurantController::class, 'getMyRestaurants']);

    //updated my restaurants
    Route::post('/restaurant/updated', [RestaurantController::class, 'updateRestaurant']);
    Route::post('/restaurant/updatedImages/{restaurantId}', [RestaurantController::class, 'updatedImages']);
    Route::post('/restaurant/updatedOpening/{restaurantId}', [RestaurantController::class, 'updatedOpenings']);
    Route::post('/restaurant/updateCategories/{restaurantId}', [RestaurantController::class, 'updateCategories']);

    //verify 
    // Route::post('/verified/insert', [RestaurantController::class, 'verifiedRestaurant']);
    Route::post('/verified/insert/{restaurantId}', [RestaurantController::class, 'verifiedRestaurant']);
    Route::post('/verified/insertImages/{restaurantId}', [RestaurantController::class, 'insertImagesForVerified']);
});

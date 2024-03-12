<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'verifyLogin']);

Route::middleware('auth:sanctum')->group(function () {

    //user
    Route::get('/users', [UserController::class, 'getUser']);
    Route::get('/user/{id}', [UserController::class, 'getUserbyID']);
    Route::post('/user/edit/name', [UserController::class, 'updateName']);
    Route::post('/user/edit/email', [UserController::class, 'updateEmail']);
    Route::delete('/user/delete/{user_id}', [UserController::class, 'deleteAccount']);

    //restaurant
    Route::post('/restaurant/insert', [RestaurantController::class, 'insertRestaurant']);
    Route::delete('/restaurant/delete/{restaurant_id}', [RestaurantController::class, 'deleteRestaurant']);

    //review
    Route::post('/review/insert', [ReviewController::class, 'insertReview']);
    Route::post('/review/update', [ReviewController::class, 'updateReview']);
    Route::delete('/review/delete/{review_id}', [ReviewController::class, 'deleteR']);
});

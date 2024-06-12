<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/login', function () {
    return view('login.login');
})->name('login');

Route::post('/verify_login', [App\Http\Controllers\UserController::class, 'verified_login_for_admin'])->name('verify_login');

Route::middleware('web')->group(function () {

    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('/');

    //report users
    Route::get('/report_user', [App\Http\Controllers\UserController::class, 'reportInfoUser'])->name('report-info-user');
    Route::get('/report_restaurant',  [App\Http\Controllers\RestaurantController::class, 'getAllRestaurant'])->name('report-info-restaurant');

    //verify
    Route::get('/report-verify-restaurant',  [App\Http\Controllers\RestaurantController::class, 'getAllRestaurantForVerify'])->name('report-verify-restaurant');
    Route::put('/update-verify/{id}/{userId}', [App\Http\Controllers\RestaurantController::class, 'updateStatusForVerify'])->name('update-verify');

    //report restaurant by user
    Route::get('/report-restaurant-by-user', [App\Http\Controllers\RestaurantController::class, 'getReportRestaurantForReportUser'])->name('report-restaurant-by-user');
    //users
    Route::get('/user_management', [App\Http\Controllers\UserController::class, 'reportInfoUserAndAdjustStatus'])->name('user-management');
    Route::put('/update-status/{id}', [App\Http\Controllers\UserController::class, 'updateStatus'])->name('update-status');
    Route::delete('/delete-user/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('delete-user');

    //restaurants
    Route::get('/restaurant_management', [App\Http\Controllers\RestaurantController::class, 'getAllRestaurantForManagement'])->name('restaurant-management');
    Route::delete('/delete-restaurant/{id}', [App\Http\Controllers\RestaurantController::class, 'deleteRestaurantForManagement'])->name('delete-restaurant');

    Route::get('/admin-management', function () {
        return view('management/admin_management');
    })->name('admin-management');

    Route::get('/update-personal', function () {
        return view('update/update_personal');
    })->name('update-personal');

    Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout_for_admin'])->name('logout');
});

<?php

use Illuminate\Support\Facades\Route;



// Route::get('/', function () {
//     return view('index');
// })->name('/');

Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('/');


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// login
Route::get('/login', function () {
    return view('login/login');
})->name('login');



//////////////////////// report data for report ////////////////////////
//report users
Route::get('/report_user', [App\Http\Controllers\UserController::class, 'reportInfoUser'])->name('report-info-user');


Route::get('/report_restaurant',  [App\Http\Controllers\RestaurantController::class, 'getAllRestaurant'])->name('report-info-restaurant');

Route::get('/report-verify-restaurant', function () {
    return view('report/report_verify_restaurant');
})->name('report-verify-restaurant');

Route::get('/report-restaurant-by-user', function () {
    return view('report/report_restaurant_by_user');
})->name('report-restaurant-by-user');

//////////////////////// managements ////////////////////////

Route::get('/user_management', [App\Http\Controllers\UserController::class, 'reportInfoUserAndAdjustStatus'])->name('user-management');
Route::put('/update-status/{id}', [App\Http\Controllers\UserController::class, 'updateStatus'])->name('update-status');
Route::delete('/delete-user/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('delete-user');




Route::get('/restaurant-management', function () {
    return view('management/restaurant_management');
})->name('restaurant-management');

Route::get('/admin-management', function () {
    return view('management/admin_management');
})->name('admin-management');

/////////////////////// update //////////////////////
Route::get('/update-personal', function () {
    return view('update/update_personal');
})->name('update-personal');



/////////////////////// logout //////////////////////
Route::get('/logout', function () {
    return view('login/login');
})->name('logout');


// Route::get('/admin-management', function () {
//     return view('admin_management');
// })->name('admin-management');

// Route::get('/edit-personal', function () {
//     return view('edit/edit_personal');
// })->name('edit-personal');
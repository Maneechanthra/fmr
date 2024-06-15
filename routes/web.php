<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\dashboard\AuthController;

Route::get('/', function () {
      return view('login.login');
});


Route::controller(AuthController::class)->group(function () {
      Route::get('/registration', 'registration')->middleware('alreadyLoggedIn');
      Route::post('/registration-user', 'registerUser')->name('register-user');
      Route::get('/login', 'login')->middleware('alreadyLoggedIn');
      Route::post('/login-user', 'loginUser')->name('login-user');
      // Route::get('/dashboard', 'dashboard')->middleware('isLoggedIn');

      Route::get('/logout', 'logout');
});

// Route::get('/', [App\Http\Controllers\dashboard\IndexController::class, 'index'])->name('/')->middleware('isLoggedIn');

Route::middleware('isLoggedIn')->group(function () {

      Route::get('/', [App\Http\Controllers\dashboard\IndexController::class, 'index'])->name('/');
      //  report users
      Route::get('/report_user', [App\Http\Controllers\dashboard\UserController::class, 'reportInfoUser'])->name('report-info-user');
      Route::get('/report_restaurant',  [App\Http\Controllers\dashboard\RestaurantController::class, 'getAllRestaurant'])->name('report-info-restaurant');

      //verify
      Route::get('/report-verify-restaurant',  [App\Http\Controllers\dashboard\RestaurantController::class, 'getAllRestaurantForVerify'])->name('report-verify-restaurant');
      Route::put('/update-verify/{id}/{userId}', [App\Http\Controllers\dashboard\RestaurantController::class, 'updateStatusForVerify'])->name('update-verify');
      Route::put('/reject-verify/{id}/{userId}', [App\Http\Controllers\dashboard\RestaurantController::class, 'updateStatusForReport'])->name('reject-verify');

      //report restaurant by user
      Route::get('/report-restaurant-by-user', [App\Http\Controllers\dashboard\ReportController::class, 'getReportRestaurantForReportUser'])->name('report-restaurant-by-user');
      Route::put('/update-status-restaurant/{id}/{userId}', [App\Http\Controllers\dashboard\ReportController::class, 'updateStatusRestaurant'])->name('update-status-restaurant');

      //users
      Route::get('/user_management', [App\Http\Controllers\dashboard\UserController::class, 'reportInfoUserAndAdjustStatus'])->name('user-management');
      Route::put('/update-status/{id}', [App\Http\Controllers\dashboard\UserController::class, 'updateStatus'])->name('update-status');
      Route::delete('/delete-user/{id}', [App\Http\Controllers\dashboard\UserController::class, 'deleteUser'])->name('delete-user');

      //restaurants
      Route::get('/restaurant_management', [App\Http\Controllers\dashboard\RestaurantController::class, 'getAllRestaurantForManagement'])->name('restaurant-management');
      Route::delete('/delete-restaurant/{id}', [App\Http\Controllers\dashboard\RestaurantController::class, 'deleteRestaurantForManagement'])->name('delete-restaurant');

      //admin
      Route::get('/admin-management', [App\Http\Controllers\dashboard\UserController::class, 'getAdmin'])->name('admin-management');

      // Route::get('/personal-info', [App\Http\Controllers\dashboard\UserController::class, 'profileUser'])->name('personal-info');

      Route::get('/personal-info', function () {
            return view('information/personal_info');
      })->name('personal-info');
});


// Route::get('/login', function () {
//       return view('login.login');
// })->name('login');

// Route::post('/login_for_admin', [App\Http\Controllers\dashboard\AuthController::class, 'verified_login_for_admin'])->name('login_for_admin');

// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
//       // Route::middleware('web')->group(function () {

//       Route::get('/dashboard', function () {
//             return view('auth.forgot-password');
//       })->name('dashboard');

//       Route::get('/', [App\Http\Controllers\dashboard\IndexController::class, 'index'])->name('/');

//       //report users
//       Route::get('/report_user', [App\Http\Controllers\dashboard\UserController::class, 'reportInfoUser'])->name('report-info-user');
//       Route::get('/report_restaurant',  [App\Http\Controllers\dashboard\RestaurantController::class, 'getAllRestaurant'])->name('report-info-restaurant');

//       //verify
//       Route::get('/report-verify-restaurant',  [App\Http\Controllers\dashboard\RestaurantController::class, 'getAllRestaurantForVerify'])->name('report-verify-restaurant');
//       Route::put('/update-verify/{id}/{userId}', [App\Http\Controllers\dashboard\RestaurantController::class, 'updateStatusForVerify'])->name('update-verify');
//       Route::put('/reject-verify/{id}/{userId}', [App\Http\Controllers\dashboard\RestaurantController::class, 'updateStatusForReport'])->name('reject-verify');

//       //report restaurant by user
//       Route::get('/report-restaurant-by-user', [App\Http\Controllers\dashboard\ReportController::class, 'getReportRestaurantForReportUser'])->name('report-restaurant-by-user');
//       Route::put('/update-status-restaurant/{id}/{userId}', [App\Http\Controllers\dashboard\ReportController::class, 'updateStatusRestaurant'])->name('update-status-restaurant');

//       //users
//       Route::get('/user_management', [App\Http\Controllers\dashboard\UserController::class, 'reportInfoUserAndAdjustStatus'])->name('user-management');
//       Route::put('/update-status/{id}', [App\Http\Controllers\dashboard\UserController::class, 'updateStatus'])->name('update-status');
//       Route::delete('/delete-user/{id}', [App\Http\Controllers\dashboard\UserController::class, 'deleteUser'])->name('delete-user');

//       //restaurants
//       Route::get('/restaurant_management', [App\Http\Controllers\dashboard\RestaurantController::class, 'getAllRestaurantForManagement'])->name('restaurant-management');
//       Route::delete('/delete-restaurant/{id}', [App\Http\Controllers\dashboard\RestaurantController::class, 'deleteRestaurantForManagement'])->name('delete-restaurant');

//       //admin
//       Route::get('/admin-management', [App\Http\Controllers\dashboard\UserController::class, 'getAdmin'])->name('admin-management');

//       // Route::get('/admin-management', function () {
//       //     return view('management/admin_management');
//       // })->name('admin-management');

//       Route::get('/update-personal', function () {
//             return view('update/update_personal');
//       })->name('update-personal');

//       Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout_for_admin'])->name('logout');
// });
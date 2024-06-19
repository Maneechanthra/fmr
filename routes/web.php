<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\dashboard\AuthController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;


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

///===================================================================================================
//images for website map
// Route::get('/storage/images/verified/{file_name}', function ($filename) {
//       $path = storage_path('app/public/verified/' . $filename);
//       if (!File::exists($path)) {
//             abort(404);
//       }
//       $file = File::get($path);
//       $type = File::mimeType($path);
//       $response = Response::make($file, 200);
//       $response->header("Content-Type", $type);
//       return $response;
// });
///===================================================================================================

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

      Route::get('/images_verified', [Controller::class, 'sentimgReview']);

      Route::get('/public/verified/{file_name}', function ($filename) {
            $path = storage_path('app/public/verified/' . $filename);
            if (!File::exists($path)) {
                  abort(404);
            }
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
      });
});


Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

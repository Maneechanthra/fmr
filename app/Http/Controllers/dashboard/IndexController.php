<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //

    public function index(Request $request)
    {
        $userData = $request->session()->get('user_data');

        // if (!$userData) {
        //     return redirect()->route('login');
        // }

        $information = DB::table('users')
            ->leftJoin('restaurants', 'users.id', '=', 'restaurants.created_by')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->whereNull('users.deleted_at')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'restaurants.id as restaurant_id',
                'restaurants.restaurant_name as restaurant_name'
            )
            ->get();


        $totalUsers = DB::table('users')->whereNull('deleted_at')->count();

        $restaurantsCount = DB::table('restaurants')->whereNull('deleted_at')->count();

        $topRestaurants = DB::table('restaurants')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count'),
                'restaurants.status',
                'restaurants.verified',
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN restaurant_favorites.deleted_at IS NULL THEN restaurant_favorites.id ELSE NULL END) as favorites_count'),
            )
            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.status',
                'restaurants.verified'
            )->orderBy('view_count', 'desc')
            ->get();

        return view('index', [
            'userData' => $userData,
            'data' => $information,
            'total_users' => $totalUsers,
            'restaurantsCount' => $restaurantsCount,
            'topRestaurants' => $topRestaurants,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\restaurant_favorites;
use Illuminate\Support\Facades\DB;

class FavoritesController extends Controller
{
    use SoftDeletes;

    public function insertFavorites(Request $request)
    {
        $favorites = new restaurant_favorites();
        $favorites->restaurant_id = $request->input('restaurant_id');
        $favorites->favorite_by = $request->input('favorite_by');
        $favorites->save();

        return $favorites;
    }


    public function deleteFavorites($favorites_id)
    {
        $favorites = restaurant_favorites::find($favorites_id);
        $favorites->delete();
        return response()->json(['success' => true, 'message' => 'Favorites deleted successfully'], 200);
    }

    public function getMyFavorites($user_id)
    {
        $myfavorite = DB::table('restaurants')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.verified',
                'restaurants.status',
                DB::raw('GROUP_CONCAT(DISTINCT categories.title) as category_titles'),
                DB::raw('MIN(restaurant_images.path) as image_path'),
                DB::raw('AVG(restaurant_reviews.rating) as average_rating'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_favorites.id), 0) as favorites_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count')
            )
            ->join('restaurant_categories', 'restaurants.id', '=', 'restaurant_categories.restaurant_id')
            ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
            ->leftJoin('restaurant_images', function ($join) {
                $join->on('restaurants.id', '=', 'restaurant_images.restaurant_id')
                    ->whereNull('restaurant_images.deleted_at');
            })
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->where('restaurant_favorites.favorite_by', '=', $user_id)
            ->whereNull('restaurant_favorites.deleted_at')
            ->whereNull('restaurants.deleted_at')
            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.verified',
                'restaurants.status'
            )
            ->get();


        $myfavorite->transform(function ($restaurant) {
            $restaurant->restaurant_category = explode(',', $restaurant->category_titles);
            unset($restaurant->category_titles);
            return $restaurant;
        });

        return response()->json($myfavorite);
    }

    public function checkFavorite($user_id, $restaurant_id)
    {
        $checkFavorites = DB::table('restaurant_favorites')
            ->select(
                'restaurant_favorites.id',
                'restaurant_favorites.favorite_by',
                'restaurant_favorites.restaurant_id'
            )
            ->leftJoin('users', 'users.id', '=', 'restaurant_favorites.favorite_by')
            ->where('restaurant_favorites.favorite_by', '=', $user_id)
            ->where('restaurant_favorites.restaurant_id', '=', $restaurant_id)
            ->whereNull('restaurant_favorites.deleted_at')

            ->get();

        if ($checkFavorites->count() > 0) {
            return array_merge(['status' => 1], ['favorites' => $checkFavorites]);
        } else {
            return array_merge(['status' => 0], ['favorites' => $checkFavorites]);
        }
    }
}


// DB::raw('IFNULL(COUNT(DISTINCT restaurant_favorites.id), 0) as favorites_count')
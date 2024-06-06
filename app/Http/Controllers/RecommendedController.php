<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\restaurant_categories;
use App\Models\restaurant_images;
use App\Models\restaurants;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RecommendedController extends Controller
{
    public function getRecommended()
    {
        $restaurants = DB::table('restaurants')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                // 'categories.title',
                'restaurants.verified',
                'restaurants.latitude',
                'restaurants.longitude',
                // 'categories.title as category_title',
                DB::raw('MIN(restaurant_images.path) as image_path'),
                DB::raw('AVG(restaurant_reviews.rating) as average_rating'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                // DB::raw('COUNT(restaurant_reviews.id) as review_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_favorites.id), 0) as favorites_count'),
            )
            // ->join('restaurant_categories', 'restaurants.id', '=', 'restaurant_categories.restaurant_id')
            // ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
            ->join('restaurant_images', function ($join) {
                $join->on('restaurants.id', '=', 'restaurant_images.restaurant_id')
                    ->whereNull('restaurant_images.deleted_at');
            })
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->whereNull('restaurants.deleted_at')
            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                // 'categories.title',
                'restaurants.verified',
                // 'category_title',
                'restaurants.latitude',
                'restaurants.longitude',

            )
            ->distinct()
            ->get();

        foreach ($restaurants as $restaurant) {
            $category_restaurant = DB::table('restaurant_categories')
                ->select('categories.title as category_title',)

                ->join('restaurants', 'restaurant_categories.restaurant_id', '=', 'restaurants.id')
                ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
                ->where('restaurant_id', $restaurant->id)
                ->whereNull('restaurant_categories.deleted_at')
                ->pluck('category_title');

            $restaurant->restaurant_category = $category_restaurant;
        }

        return response()->json(['success' => true, 'restaurants' => $restaurants]);
    }
}

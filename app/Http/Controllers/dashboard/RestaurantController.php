<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use Symfony\Component\HttpFoundation\Response;
use App\Models\restaurants;

class RestaurantController extends Controller
{

    use SoftDeletes;

    public function getAllRestaurant()
    {

        $restaurantsCount = DB::table('restaurants')->whereNull('deleted_at')->count();
        $restaurant = DB::table('restaurants')
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

        return view('report.report_restaurant', [
            'data' => $restaurant,
            'restaurantsCount' => $restaurantsCount,

        ]);
    }
    // public function getAllRestaurantForVerify()
    // {

    //     $verifCount = DB::table('restaurants')
    //         ->where('restaurants.verified', '=', '1')
    //         ->whereNull('restaurants.deleted_at')
    //         ->count();

    //     $restaurantsCount = DB::table('restaurants')->whereNull('deleted_at')->count();
    //     $restaurant = DB::table('restaurants')
    //         ->leftJoin('users', 'restaurants.created_by', '=', 'users.id')
    //         ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
    //         ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
    //         ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
    //         ->whereNull('restaurants.deleted_at')
    //         ->select(
    //             'users.id as user_id',
    //             'users.name as user_name',
    //             'restaurants.id',
    //             'restaurants.restaurant_name',
    //             'restaurants.address',
    //             'restaurants.telephone_1',
    //             'restaurants.telephone_2',
    //             DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count'),
    //             'restaurants.status',
    //             'restaurants.verified',

    //             DB::raw('COUNT(CASE WHEN restaurants.verified = 1 THEN 1 ELSE NULL END) as verified_count'),
    //             DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
    //             DB::raw('COUNT(DISTINCT CASE WHEN restaurant_favorites.deleted_at IS NULL THEN restaurant_favorites.id ELSE NULL END) as favorites_count'),
    //         )
    //         ->where('restaurants.verified', 1)
    //         ->groupBy(
    //             'users.id',
    //             'users.name',
    //             'restaurants.id',
    //             'restaurants.restaurant_name',
    //             'restaurants.address',
    //             'restaurants.telephone_1',
    //             'restaurants.telephone_2',
    //             'restaurants.status',
    //             'restaurants.verified'
    //         )->get();

    //     foreach ($restaurant as $restaurants) {
    //         $restaurants->image_paths = DB::table('restaurant_images')
    //             ->where('restaurant_id', $restaurants->id)
    //             ->whereNull('deleted_at')
    //             ->where('path', 'like', 'verified/%')
    //             ->pluck('path');
    //     }

    //     return view('report.report_verify_restaurant', [
    //         'restaurants' => $restaurant,
    //         'restaurantsCount' => $restaurantsCount,
    //         'verifCount' => $verifCount,

    //     ]);
    // }

    public function getAllRestaurantForVerify()
    {
        $verifCount = DB::table('restaurants')
            ->where('restaurants.verified', '=', '1')
            ->whereNull('restaurants.deleted_at')
            ->count();

        $restaurantsCount = DB::table('restaurants')->whereNull('deleted_at')->count();

        $restaurants = DB::table('restaurants')
            ->leftJoin('users', 'restaurants.created_by', '=', 'users.id')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count'),
                'restaurants.status',
                'restaurants.verified',
                DB::raw('COUNT(CASE WHEN restaurants.verified = 1 THEN 1 ELSE NULL END) as verified_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN restaurant_favorites.deleted_at IS NULL THEN restaurant_favorites.id ELSE NULL END) as favorites_count')
            )
            ->where('restaurants.verified', 1)
            ->groupBy(
                'users.id',
                'users.name',
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.status',
                'restaurants.verified'
            )->get();

        foreach ($restaurants as $restaurant) {
            $restaurant->image_paths = DB::table('restaurant_images')
                ->where('restaurant_id', $restaurant->id)
                ->whereNull('deleted_at')
                ->where('path', 'like', 'verified/%')
                ->pluck('path');
        }

        return view('report.report_verify_restaurant', [
            'restaurants' => $restaurants,
            'restaurantsCount' => $restaurantsCount,
            'verifCount' => $verifCount,
        ]);
    }



    public function updateStatusForVerify($id, $userId)
    {
        DB::table('restaurants')
            ->where('id', $id)
            ->update(['verified' => 2, 'updated_by' => $userId]);

        return redirect()->back()->with('success', 'verified updated successfully');
    }

    public function updateStatusForReport(Request $request, $id, $userId)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'updated_by' => 'required|integer',
            'reject_detail' => 'required|string',
        ]);

        $id = $validatedData['id'];
        $userId = $validatedData['updated_by'];
        $rejectReason = $validatedData['reject_detail'];

        DB::table('restaurants')
            ->where('id', $id)
            ->update(['verified' => 0, 'updated_by' => $userId, 'reject_detail' => $rejectReason]);

        return response()->json(['success' => true, 'message' => 'verified updated successfully']);
    }

    public function getAllRestaurantForManagement()
    {

        $restaurantsCount = DB::table('restaurants')->whereNull('deleted_at')->count();
        $restaurant = DB::table('restaurants')
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
            )->get();

        return view('management.restaurant_management', [
            'restaurants' => $restaurant,
            'restaurantsCount' => $restaurantsCount,

        ]);
    }

    public function deleteRestaurantForManagement($restaurant_id)
    {
        $restaurants = restaurants::find($restaurant_id);
        if ($restaurants) {
            $restaurants->delete();
            return redirect()->back()->with('success', 'delete success');
        } else {
            return response()->json(array('error' => Response::HTTP_FORBIDDEN, 'message' => null));
        }
    }
}

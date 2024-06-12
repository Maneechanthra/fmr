<?php

namespace App\Http\Controllers;

use App\Models\restaurant_categories;
use App\Models\restaurant_images;
use Illuminate\Http\Request;
use App\Models\restaurants;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\restaurant_openings;
use Carbon\Carbon;

class RestaurantController extends Controller
{
    use SoftDeletes;


    // insert restaurant
    public function createRestaurant(Request $request)
    {
        $restaurant = new restaurants();
        $restaurant->restaurant_name = $request->input('restaurant_name');
        $restaurant->address = $request->input('address');
        $restaurant->telephone_1 = $request->input('telephone_1');
        $restaurant->telephone_2 = $request->input('telephone_2');
        $restaurant->latitude = $request->input('latitude');
        $restaurant->longitude = $request->input('longitude');
        $restaurant->created_by = $request->input('created_by');
        $restaurant->save();

        return response()->json([
            'restaurant' => $restaurant,
            'restaurant_id' => $restaurant->id,
        ]);
    }

    public function insertCategories(Request $request, $restaurantId)
    {
        $data = $request->input('category_data');
        foreach ($data as $item) {
            $category = new restaurant_categories();
            $category->restaurant_id = $restaurantId;
            $category->category_id = $item['category_id'];
            $category->save();
        }

        return response()->json([
            'category' => $category,
        ]);
    }

    public function insertOpenings(Request $request, $restaurantId)
    {
        $data = $request->input('openings');
        if (!empty($data) && is_array($data)) {
            foreach ($data as $opening) {
                if (isset($opening['day_open']) && isset($opening['time_open']) && isset($opening['time_close'])) {
                    $openTime = new restaurant_openings();
                    $openTime->restaurant_id = $restaurantId;
                    $openTime->day_open = $opening['day_open'];
                    $time_open = Carbon::createFromFormat('g:i A', $opening['time_open'])->format('H:i:s');
                    $time_close = Carbon::createFromFormat('g:i A', $opening['time_close'])->format('H:i:s');
                    $openTime->time_open = $time_open;
                    $openTime->time_close = $time_close;
                    $openTime->save();
                }
            }
        }
        return response()->json([
            'openTime' => $openTime,
        ]);
    }


    public function insertImages(Request $request, $restaurantId)
    {
        if ($request->hasFile('path')) {
            $images = [];
            foreach ($request->file('path') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = $restaurantId . '_' . uniqid() . '.' . $extension;
                    $image->storeAs('public/restaurants', $filename);
                    $images[] = 'restaurants/' . $filename;
                }
            }
            foreach ($images as $imagePath) {
                $restaurantImage = new restaurant_images();
                $restaurantImage->restaurant_id = $restaurantId;
                $restaurantImage->path = $imagePath;
                $restaurantImage->save();
            }
        }
        return response()->json([
            'restaurant_images' => $restaurantImage,
        ]);
    }

    //delete restaurants 
    public function deleteRestaurant($restaurant_id)
    {
        $restaurant = restaurants::find($restaurant_id);
        $restaurant->delete();
        return response()->json(array('success' => true, 'message' => 'Restaurant deleted successfully'), 200);
    }


    // get restaurant by id
    public function getRestaurantById($restaurant_id)
    {
        $restaurant = DB::table('restaurants')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.verified',
                'restaurants.created_by',
                // 'restaurant_openings.time_open',
                // 'restaurant_openings.time_close',
                DB::raw('IFNULL(AVG(restaurant_reviews.rating), 0) as average_rating'),
                // DB::raw('COUNT(DISTINCT restaurant_reviews.id) as review_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN restaurant_reviews.deleted_at IS NULL THEN restaurant_reviews.id ELSE NULL END) as review_count'),

                DB::raw('COUNT(DISTINCT restaurant_favorites.id) as favorites_count'),
                DB::raw('COUNT(DISTINCT restaurant_views.id) as view_count')
            )
            ->leftJoin('restaurant_categories', 'restaurants.id', '=', 'restaurant_categories.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_openings', 'restaurants.id', '=', 'restaurant_openings.restaurant_id')
            ->where('restaurants.id', $restaurant_id)
            ->whereNull('restaurants.deleted_at')
            // ->whereNull('restaurant_reviews.deleted_at')

            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.verified',
                'restaurants.created_by',
                // 'restaurant_openings.time_open',
                // 'restaurant_openings.time_close',
            )
            ->first();

        if (!$restaurant) {
            return response()->json(['success' => false, 'message' => 'Restaurant not found'], 404);
        }

        // Retrieve restaurant categories
        $restaurant->restaurant_category = DB::table('restaurant_categories')
            ->select('categories.title as category_title')
            ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
            ->where('restaurant_id', $restaurant_id)
            ->whereNull('restaurant_categories.deleted_at')
            ->pluck('category_title');

        $restaurant->image_paths = DB::table('restaurant_images')
            ->where('restaurant_id', $restaurant_id)
            ->whereNull('deleted_at')
            ->where('path', 'like', 'restaurants/%')
            ->pluck('path');


        // Retrieve restaurant opeinng
        $restaurant->openings = DB::table('restaurant_openings')
            ->where('restaurant_id', $restaurant_id)
            ->whereNull('deleted_at')
            ->get();

        // Retrieve restaurant reviews
        $restaurant->reviews = DB::table('restaurant_reviews')
            ->select(
                'restaurant_reviews.title',
                'restaurant_reviews.content',
                'restaurant_reviews.id',
                'restaurant_reviews.rating',
                'users.name',
                'restaurant_reviews.created_at'
            )
            ->join('users', 'users.id', '=', 'restaurant_reviews.review_by')
            ->where('restaurant_id', $restaurant_id)
            ->whereNull('restaurant_reviews.deleted_at')
            ->get();

        // Retrieve image paths for each review
        foreach ($restaurant->reviews as $review) {
            $review->image_paths_review = DB::table('restaurant_image_reviews')
                ->where('review_id', $review->id)

                ->pluck('path');
        }

        return response()->json($restaurant);
    }


    //updated restaurant
    public function updateRestaurant(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $restaurant = restaurants::find($request->id);
        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        if ($request->has('restaurant_name')) {
            $restaurant->restaurant_name = $request->restaurant_name;
        }
        if ($request->has('telephone_1')) {
            $restaurant->telephone_1 = $request->telephone_1;
        }
        if ($request->has('telephone_2')) {
            $restaurant->telephone_2 = $request->telephone_2;
        }
        if ($request->has('latitude')) {
            $restaurant->latitude = $request->latitude;
        }
        if ($request->has('longitude')) {
            $restaurant->longitude = $request->longitude;
        }
        if ($request->has('address')) {
            $restaurant->address = $request->address;
        }

        $restaurant->save();

        return response()->json($restaurant);
    }
    public function updatedImages(Request $request, $restaurantId)
    {
        $restaurantImage = null;

        if ($request->hasFile('path')) {
            $images = [];
            foreach ($request->file('path') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = $restaurantId . '_' . time() . '_' . uniqid() . '.' . $extension;
                    $image->storeAs('public/restaurants', $filename);
                    $images[] = 'restaurants/' . $filename;
                }
            }

            $oldImages = restaurant_images::where('restaurant_id', $restaurantId)
                ->where('path', 'LIKE', 'restaurants/%')
                ->get();

            foreach ($oldImages as $oldImage) {
                Storage::delete('public/' . $oldImage->path);
                $oldImage->delete();
            }

            foreach ($images as $imagePath) {
                $restaurantImage = new restaurant_images;
                $restaurantImage->restaurant_id = $restaurantId;
                $restaurantImage->path = $imagePath;
                $restaurantImage->save();
            }
        }

        return response()->json([
            'restaurant_images' => $restaurantImage,
        ]);
    }

    public function updatedOpenings(Request $request, $restaurantId)
    {
        $data = $request->input('openings');
        if (!empty($data) && is_array($data)) {
            // ลบข้อมูลเก่าก่อน
            restaurant_openings::where('restaurant_id', $restaurantId)->delete();

            foreach ($data as $opening) {
                if (isset($opening['day_open']) && isset($opening['time_open']) && isset($opening['time_close'])) {
                    $openTime = new restaurant_openings();
                    $openTime->restaurant_id = $restaurantId;
                    $openTime->day_open = $opening['day_open'];
                    $time_open = Carbon::createFromFormat('g:i A', $opening['time_open'])->format('H:i:s');
                    $time_close = Carbon::createFromFormat('g:i A', $opening['time_close'])->format('H:i:s');
                    $openTime->time_open = $time_open;
                    $openTime->time_close = $time_close;
                    $openTime->save();
                }
            }
        }

        return response()->json([
            'message' => 'Opening hours updated successfully.',
            'openings' =>    $openTime,
        ]);
    }


    public function updateCategories(Request $request, $restaurantId)
    {
        $data = $request->input('category_data');

        restaurant_categories::where('restaurant_id', $restaurantId)->delete();

        foreach ($data as $item) {
            $category = new restaurant_categories();
            $category->restaurant_id = $restaurantId;
            $category->category_id = $item['category_id'];
            $category->save();
        }

        return response()->json([
            'message' => 'Categories updated successfully.',

        ]);
    }


    // get my restaurants
    public function getMyRestaurants($userId)
    {
        $restaurants = DB::table('restaurants')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.verified',
                'restaurants.status',
                'restaurants.created_by',
                'users.name',

                // DB::raw('GROUP_CONCAT(DISTINCT categories.title) as categories'),
                DB::raw('MIN(restaurant_images.path) as image_path'),
                DB::raw('IFNULL(AVG(restaurant_reviews.rating), 0) as average_rating'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_favorites.id), 0) as favorites_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reports.id), 0) as reports_count')
            )
            ->leftJoin('restaurant_categories', 'restaurants.id', '=', 'restaurant_categories.restaurant_id')
            ->leftJoin('categories', 'restaurant_categories.category_id', '=', 'categories.id')
            ->leftJoin('restaurant_images', function ($join) {
                $join->on('restaurants.id', '=', 'restaurant_images.restaurant_id')
                    ->whereNull('restaurant_images.deleted_at');
            })
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->leftJoin('restaurant_reports', 'restaurants.id', '=', 'restaurant_reports.restaurant_id')
            ->leftJoin('users', 'restaurants.created_by', '=', 'users.id')
            ->where('restaurants.created_by', '=', $userId)
            ->whereNull('restaurants.deleted_at')

            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.verified',
                'restaurants.status',
                'restaurants.created_by',
                'users.name',
            )
            ->get();

        foreach ($restaurants as $restaurant) {
            // Retrieve restaurant categories
            $restaurant->restaurant_categories = DB::table('restaurant_categories')
                ->select('categories.id', 'categories.title as category_title')
                ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
                ->where('restaurant_id', $restaurant->id)
                ->whereNull('restaurant_categories.deleted_at')
                ->get()
                ->map(function ($category) {
                    return [
                        "id" => $category->id,
                        "category_title" => $category->category_title
                    ];
                });




            // Retrieve restaurant images
            $restaurant->image_paths = DB::table('restaurant_images')
                ->where('restaurant_id', $restaurant->id)
                ->whereNull('deleted_at')
                ->where('path', 'like', 'restaurants/%')
                ->pluck('path');

            // Retrieve restaurant openings
            $restaurant->openings = DB::table('restaurant_openings')
                ->where('restaurant_id', $restaurant->id)
                ->whereNull('restaurant_openings.deleted_at')
                ->get();

            // Retrieve restaurant reviews
            $restaurant->reviews = DB::table('restaurant_reviews')
                ->select(
                    'restaurant_reviews.title',
                    'restaurant_reviews.content',
                    'restaurant_reviews.id',
                    'restaurant_reviews.rating',
                    'users.name',
                    'restaurant_reviews.created_at'
                )
                ->join('users', 'users.id', '=', 'restaurant_reviews.review_by')
                ->where('restaurant_id', $restaurant->id)
                // ->whereNull('restaurant_reviews.deleted_at')
                ->get();

            // Retrieve image paths for each review
            foreach ($restaurant->reviews as $review) {
                $review->image_paths_review = DB::table('restaurant_image_reviews')
                    ->where('review_id', $review->id)
                    // ->whereNull('restaurant_reviews.deleted_at')
                    ->pluck('path');
            }
        }

        return response()->json($restaurants);
    }



    // get search restaurants
    public function getRestaurantsSearchByName()
    {
        $restaurants = DB::table('restaurants')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'categories.title',
                'restaurants.verified',
                'restaurants.status',
                // 'restaurants.longitude',
                'categories.title as category_title',
                DB::raw('MIN(restaurant_images.path) as image_path'),
                DB::raw('AVG(restaurant_reviews.rating) as average_rating'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_favorites.id), 0) as favorites_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count'),
            )
            ->join('restaurant_categories', 'restaurants.id', '=', 'restaurant_categories.restaurant_id')
            ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
            ->join('restaurant_images', function ($join) {
                $join->on('restaurants.id', '=', 'restaurant_images.restaurant_id')
                    ->whereNull('restaurant_images.deleted_at');
            })
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'categories.title',
                'restaurants.verified',
                'category_title',
                'restaurants.status',
                // 'restaurants.longitude',

            )
            ->get();

        return response()->json($restaurants);
    }

    //restaurants for map page
    public function getRestaurantForMap()
    {
        $restaurants = DB::table('restaurants')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.verified',
                DB::raw('IFNULL(AVG(restaurant_reviews.rating), 0) as average_rating'),
                DB::raw('COALESCE(COUNT(DISTINCT restaurant_reviews.id), 0) as review_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_favorites.id), 0) as favorites_count'),
                DB::raw('IFNULL(COUNT(DISTINCT restaurant_views.id), 0) as view_count')
            )
            ->join('restaurant_categories', 'restaurants.id', '=', 'restaurant_categories.restaurant_id')
            ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
            ->leftJoin('restaurant_favorites', 'restaurants.id', '=', 'restaurant_favorites.restaurant_id')
            ->leftJoin('restaurant_views', 'restaurants.id', '=', 'restaurant_views.restaurant_id')
            ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
            ->whereNull('restaurants.deleted_at')
            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.verified',
            )
            ->get();

        if ($restaurants->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Restaurant not found'], 404);
        }

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

        $categories = DB::table('categories')->select('title')->get();

        foreach ($restaurants as $restaurant) {
            $image_paths = DB::table('restaurant_images')
                ->where('restaurant_id', $restaurant->id)
                ->whereNull('restaurant_images.deleted_at')
                ->pluck('path');

            $restaurant->image_paths = $image_paths;
        }

        return response()->json(['success' => true, 'restaurants' => $restaurants, 'categories' => $categories]);
    }

    // verified update restaurant
    public function verifiedRestaurant(Request $request, $restaurantId)
    {

        $request->validate([
            // 'restaurant_name' => 'required|string|max:255',
            'updated_by' => 'required|integer',
        ]);

        $restaurant = restaurants::find($restaurantId);
        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }
        // $restaurant->restaurant_name = $request->input('restaurant_name');
        // $restaurant->created_by = $request->input('created_by');
        $restaurant->verified = 1;
        $restaurant->updated_by = $request->input('updated_by');

        $restaurant->save();
        return response()->json([
            'restaurant' => $restaurant,
            'restaurant_id' => $restaurant->id,
        ]);
    }


    public function insertImagesForVerified(Request $request, $restaurantId)
    {
        if ($request->hasFile('path')) {
            $images = [];
            foreach ($request->file('path') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = $restaurantId . '_' . uniqid() . '.' . $extension;
                    $image->storeAs('public/verified', $filename);
                    $images[] = 'verified/' . $filename;
                }
            }
            foreach ($images as $imagePath) {
                $restaurantImage = new restaurant_images();
                $restaurantImage->restaurant_id = $restaurantId;
                $restaurantImage->path = $imagePath;
                $restaurantImage->save();
            }
        }
        return response()->json([
            'restaurant_images' => $restaurantImage,
        ]);
    }

    // ----------------------------------------------------------------
    // ----------------------------------------------------------------

    //all restaurant and user
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

    public function getAllRestaurantForVerify()
    {

        $verifCount = DB::table('restaurants')->where('restaurants.verified', '=', '1')->count();
        $restaurantsCount = DB::table('restaurants')->whereNull('deleted_at')->count();
        $restaurant = DB::table('restaurants')
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
                DB::raw('COUNT(DISTINCT CASE WHEN restaurant_favorites.deleted_at IS NULL THEN restaurant_favorites.id ELSE NULL END) as favorites_count'),
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

        return view('report.report_verify_restaurant', [
            'restaurants' => $restaurant,
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

    //report rsetaurant by user
    public function getReportRestaurantForReportUser()
    {
        $reportCount = DB::table('restaurant_reports')
            ->leftJoin('restaurants', 'restaurant_reports.restaurant_id', '=', 'restaurants.id')
            ->whereNull('restaurants.deleted_at')
            ->count();

        $reportCountByRestaurant = DB::table('restaurant_reports')
            ->leftJoin('restaurants', 'restaurant_reports.restaurant_id', '=', 'restaurants.id')
            ->whereNull('restaurants.deleted_at')
            ->select(
                DB::raw('COUNT(*) as report_count')
            )
            ->groupBy('restaurants.id', 'restaurants.restaurant_name')
            ->pluck('report_count');


        $reportRestaurant = DB::table('restaurant_reports')
            ->leftJoin('users', 'restaurant_reports.report_by', '=', 'users.id')
            ->leftJoin('restaurants', 'restaurant_reports.restaurant_id', '=', 'restaurants.id')
            ->whereNull('restaurants.deleted_at')
            ->select(
                'restaurant_reports.title as report_title',
                'restaurant_reports.descriptions as report_description',
                'restaurant_reports.status as report_status',
                'users.id as user_id',
                'users.name as user_name',
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.status',
                'restaurants.verified'
            )
            ->distinct()
            ->get();

        return view('report.report_restaurant_by_user', [
            'reports' => $reportRestaurant,
            'reportCount' => $reportCount,
            'reportCountByRestaurant' => $reportCountByRestaurant
        ]);
    }
}

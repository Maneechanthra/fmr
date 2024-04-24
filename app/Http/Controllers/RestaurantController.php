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

    public function insertRestaurant(Request $request)
    {
        //insert a new restaurant
        $restaurant = new restaurants();
        $restaurant->restaurant_name = $request->input('restaurant_name');
        $restaurant->address = $request->input('address');
        $restaurant->telephone_1 = $request->input('telephone_1');
        $restaurant->telephone_2 = $request->input('telephone_2');
        $restaurant->latitude = $request->input('latitude');
        $restaurant->longitude = $request->input('longitude');
        $restaurant->created_by = $request->input('created_by');
        $restaurant->save();

        //insert a new categorys
        $categories = new restaurant_categories();
        $categories->restaurant_id = $restaurant->id;
        $categories->category_id = $request->input('category_id');
        $categories->save();


        //insert new openings
        $data = $request->input('openings');
        foreach ($data as $opening) {
            if (isset($opening['day_open']) && isset($opening['time_open']) && isset($opening['time_close'])) {
                $openTime = new restaurant_openings();
                $openTime->restaurant_id = $restaurant->id;
                $openTime->day_open = $opening['day_open'];
                $time_open = Carbon::createFromFormat('g:i A', $opening['time_open'])->format('H:i:s');
                $time_close = Carbon::createFromFormat('g:i A', $opening['time_close'])->format('H:i:s');
                $openTime->time_open = $time_open;
                $openTime->time_close = $time_close;
                $openTime->save();
            }
        }

        //insert new images 
        if ($request->hasFile('path')) {
            $images = [];
            foreach ($request->file('path') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = $restaurant->id . '_' . time() . '_' . uniqid() . '.' . $extension;
                    $image->storeAs('public/restaurants', $filename);
                    $images[] = 'restaurants/' . $filename;
                }
            }
            foreach ($images as $imagePath) {
                $restaurantImage = new restaurant_images();
                $restaurantImage->restaurant_id = $restaurant->id;
                $restaurantImage->path = $imagePath;
                $restaurantImage->save();
            }
        }

        $responseData = [
            'success' => true,
            'restaurant' => $restaurant,
            'categories' => $categories,
            'opening' => $data,
        ];

        return response()->json($responseData, 200);
    }


    public function deleteRestaurant($restaurant_id)
    {
        $restaurant = restaurants::find($restaurant_id);
        $restaurant->delete();
        return response()->json(array('success' => true, 'message' => 'Restaurant deleted successfully'), 200);
    }

    //================================== รอแก้ไช ==========================================
    // public function getRestaurant()
    // {
    //     $restaurants = DB::table('restaurants')
    //         ->select(
    //             'restaurants.id',
    //             'restaurants.restaurant_name',
    //             'categories.title',
    //             DB::raw('MIN(restaurant_images.path) as image_path'), // เลือกภาพแรก
    //             DB::raw('AVG(restaurant_reviews.rating) as average_rating'),
    //             DB::raw('COUNT(restaurant_reviews.id) as review_count')
    //         )
    //         ->join('restaurant_categories', 'restaurants.id', '=', 'restaurant_categories.restaurant_id')
    //         ->join('categories', 'restaurant_categories.category_id', '=', 'categories.id')
    //         ->join('restaurant_images', 'restaurants.id', '=', 'restaurant_images.restaurant_id')
    //         ->leftJoin('restaurant_reviews', 'restaurants.id', '=', 'restaurant_reviews.restaurant_id')
    //         ->groupBy('restaurants.id') // จัดกลุ่มเฉพาะ ID ของร้านเพื่อป้องกันการซ้ำซ้อน
    //         ->distinct() // ใช้ DISTINCT เพื่อป้องกันข้อมูลซ้ำ
    //         ->take(4) // เลือกเพียง 4 ร้าน
    //         ->get();

    //     return response()->json(['success' => true, 'restaurants' => $restaurants]);
    // }

    public function getRestaurantById($restaurant_id)
    {
        // ดึงข้อมูลร้านอาหาร
        $restaurant = DB::table('restaurants')
            ->select(
                'restaurants.id',
                'restaurants.restaurant_name',
                'categories.title as category_title',
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
            ->where('restaurants.id', $restaurant_id)
            ->groupBy(
                'restaurants.id',
                'restaurants.restaurant_name',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.address',
                'restaurants.telephone_1',
                'restaurants.telephone_2',
                'restaurants.verified',
                'category_title'
            )
            ->first();

        if (!$restaurant) {
            return response()->json(['success' => false, 'message' => 'Restaurant not found'], 404);
        }

        $image_paths = DB::table('restaurant_images')
            ->where('restaurant_id', $restaurant_id)
            ->whereNull('restaurant_images.deleted_at')
            ->pluck('path');

        $restaurant->image_paths = $image_paths;


        $reviews = DB::table('restaurant_reviews')
            ->select('restaurant_reviews.title', 'restaurant_reviews.content', 'restaurant_reviews.id', 'restaurant_reviews.rating', 'users.name', 'restaurant_reviews.created_at')
            ->join('users', 'users.id', '=', 'restaurant_reviews.review_by')
            ->where('restaurant_id', $restaurant_id)
            ->get();


        foreach ($reviews as $review) {
            $image_paths_review = DB::table('restaurant_image_reviews')
                ->where('review_id', $review->id)
                ->pluck('path');

            $review->image_paths_review = $image_paths_review;
        }

        $restaurant->reviews = $reviews;

        return response()->json(['success' => true, 'restaurant' => $restaurant]);
    }

    public function updateRestaurant(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'id' => 'required',
            'restaurant_name' => 'required',
            'telephone_1' => 'required',
            'telephone_2' => 'required',
            'latitude' => 'nullable|numeric|between:-90,90', // Valid latitude
            'longitude' => 'required|numeric', // Valid longitude
            'address' => 'required',
        ]);

        // Find the restaurant by its ID
        $restaurant = restaurants::find($request->id);
        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404); // Error handling for restaurant not found
        }
        $restaurant->restaurant_name = $request->restaurant_name;
        $restaurant->telephone_1 = $request->telephone_1;
        $restaurant->telephone_2 = $request->telephone_2;
        $restaurant->latitude = $request->latitude;
        $restaurant->longitude = $request->longitude;
        $restaurant->address = $request->address;
        $restaurant->save(); // Ensure the changes are saved to the database

        // Check if there are files to upload
        if ($request->hasFile('path')) {
            $images = [];
            foreach ($request->file('path') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = $restaurant->id . '_' . time() . '_' . uniqid() . '.' . $extension;
                    $image->storeAs('public/restaurants', $filename); // Save the image
                    $images[] = 'restaurants/' . $filename; // Keep track of stored images
                }
            }

            // Delete old images from storage and the database
            $oldImages = restaurant_images::where('restaurant_id', $restaurant->id)->get();
            foreach ($oldImages as $oldImage) {
                Storage::delete('public/' . $oldImage->path); // Delete the file
                $oldImage->delete(); // Delete the database record
            }

            // Store new images in the database
            foreach ($images as $imagePath) {
                $restaurantImage = new restaurant_images;
                $restaurantImage->restaurant_id = $restaurant->id;
                $restaurantImage->path = $imagePath;
                $restaurantImage->save(); // Save the new image record
            }
        }

        // Return the updated restaurant object
        return response()->json($restaurant);
    }
}

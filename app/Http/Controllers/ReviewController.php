<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Response;
use App\Models\restaurant_reviews;
use App\Models\restaurant_image_reviews;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{

    use SoftDeletes;

    public function insertReview(Request $request)
    {
        // Create and save the review
        $review = new restaurant_reviews();
        $review->restaurant_id = $request->input('restaurant_id');
        $review->title = $request->input('title');
        $review->content = $request->input('content');
        $review->rating = $request->input('rating');
        $review->review_by = $request->input('review_by');
        $review->save();

        // Return a JSON response with the review and review ID
        return response()->json([
            'review' => $review,
            'review_id' => $review->id,
        ]);
    }

    public function insertReviewImage(Request $request, $review_id)
    {
        if ($request->hasFile('path')) {
            $images = [];
            foreach ($request->file('path') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = $review_id . '_' . uniqid() . '.' . $extension;
                    $image->storeAs('public/reviews', $filename);
                    $images[] = 'reviews/' . $filename;
                }
            }
            foreach ($images as $imagePath) {
                $restaurantImage = new restaurant_image_reviews();
                $restaurantImage->review_id = $review_id;
                $restaurantImage->path = $imagePath;
                $restaurantImage->save();
            }
        }
        return response()->json([
            'restaurant_reviews_images' => $restaurantImage,

        ]);
    }


    public function updateReview(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'rating' => 'required'
        ]);
        $review = restaurant_reviews::find($request->id);
        if ($review) {
            $review->title = $request->title;
            $review->content = $request->content;
            $review->rating = $request->rating;
            $review->save();

            if ($request->hasFile('path')) {
                $images = [];
                foreach ($request->file('path') as $image) {
                    if ($image->isValid()) {
                        $extension = $image->getClientOriginalExtension();
                        $filename = $review->id . '_' . time() . '_' . uniqid() . '.' . $extension;
                        $image->storeAs('public/reviews', $filename);
                        $images[] = 'reviews/' . $filename;
                    }
                }
                // เพิ่มโค้ดสำหรับลบรูปภาพเก่าออก
                $oldImages = restaurant_image_reviews::where('review_id', $review->id)->get();
                foreach ($oldImages as $oldImage) {
                    Storage::delete('public/' . $oldImage->path);
                    $oldImage->delete();
                }
                // สร้างรูปภาพใหม่
                foreach ($images as $imagePath) {
                    $restaurantImage = new restaurant_image_reviews;
                    $restaurantImage->review_id = $review->id;
                    $restaurantImage->path = $imagePath;
                    $restaurantImage->save();
                }
            }

            return $review;
        }
    }

    public function deleteReview($review_id)
    {
        $review = restaurant_reviews::find($review_id);
        if ($review) {
            $review->delete();
            return $review;
        } else {
            return response()->json(array('review' => $review));
        }
    }

    public function getReviewByRestaurant($restaurant_id)
    {
        $reviews = DB::table('restaurant_reviews')
            ->select(
                'restaurant_reviews.title',
                'restaurant_reviews.content',
                'restaurant_reviews.id',
                'restaurant_reviews.rating',
                'restaurant_reviews.restaurant_id',
                'users.name',
                'users.id as userId',
                'restaurant_reviews.created_at'
            )
            ->join('users', 'users.id', '=', 'restaurant_reviews.review_by')
            ->where('restaurant_id', $restaurant_id)
            ->whereNull('restaurant_reviews.deleted_at')
            ->get();

        foreach ($reviews as $review) {
            $image_paths_review = DB::table('restaurant_image_reviews')
                ->where('review_id', $review->id)
                ->pluck('path');

            $review->image_paths = $image_paths_review;
        }
        return response()->json($reviews);
    }
    public function aa()
    {
        $reviews = DB::table('restaurant_reviews')
            ->select('restaurant_reviews.title', 'restaurant_reviews.content', 'restaurant_reviews.id', 'restaurant_reviews.rating', 'restaurant_reviews.created_at')
            ->get();
        return response()->json($reviews);
    }
}

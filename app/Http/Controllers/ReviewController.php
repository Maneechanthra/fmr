<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Response;
use App\Models\restaurant_reviews;
use App\Models\restaurant_image_reviews;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

        // Initialize an empty array to store image paths
        $images = [];

        // Check if there are files to upload
        if ($request->hasFile('path')) {
            foreach ($request->file('path') as $image) {
                if ($image->isValid()) {
                    // Generate a unique file name
                    $extension = $image->getClientOriginalExtension();
                    $filename = $review->id . '_' . time() . '_' . uniqid() . '.' . $extension;
                    // Store the file in a specific location
                    $image->storeAs('public/reviews', $filename);
                    // Add the stored file path to the list
                    $images[] = 'reviews/' . $filename;
                }
            }

            // Save the image information in the database
            foreach ($images as $imagePath) {
                $restaurantImage = new restaurant_image_reviews();
                $restaurantImage->review_id = $review->id;
                $restaurantImage->path = $imagePath;
                $restaurantImage->save();
            }
        }

        // Return a JSON response with the review, review ID, and image paths
        return response()->json([
            'review' => $review,
            'review_id' => $review->id,
            'images' => $images,

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
}

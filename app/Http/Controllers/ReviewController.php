<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Response;
use App\Models\restaurant_reviews;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewController extends Controller
{

    use SoftDeletes;

    public function insertReview(Request $request)
    {
        $review = new restaurant_reviews();
        $review->restaurant_id = $request->input('restaurant_id');
        $review->title = $request->input('title');
        $review->content = $request->input('content');
        $review->rating = $request->input('rating');
        $review->review_by = $request->input('review_by');
        $review->save();

        return response()->json(array('review' => $review));
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

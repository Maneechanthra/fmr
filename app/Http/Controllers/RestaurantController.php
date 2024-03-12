<?php

namespace App\Http\Controllers;

use App\Models\restaurant_categories;
use App\Models\restaurant_images;
use Illuminate\Http\Request;
use App\Models\restaurants;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\SoftDeletes;


class RestaurantController extends Controller
{
    use SoftDeletes;

    public function insertRestaurant(Request $request)
    {
        $restaurant = new restaurants();
        $restaurant->restaurant_name = $request->input('restaurant_name');
        $restaurant->address = $request->input('address');
        $restaurant->telephone_1 = $request->input('telephone_1');
        $restaurant->telephone_2 = $request->input('telephone_2');
        $restaurant->verified = $request->input('verified');
        $restaurant->latitude = $request->input('latitude');
        $restaurant->longitude = $request->input('longitude');
        $restaurant->created_by = $request->input('created_by');
        $restaurant->save();

        $categories = new restaurant_categories;
        $categories->restaurant_id = $restaurant->id;
        $categories->category_id = $request->input('category_id');
        $categories->save();

        return response()->json(array('success' => true, 'restaurant' => $restaurant, 'categories' => $categories), 200);
    }

    public function deleteRestaurant($restaurant_id)
    {
        $restaurant = restaurants::find($restaurant_id);
        $restaurant->delete();
        return response()->json(array('success' => true, 'message' => 'Restaurant deleted successfully'), 200);
    }
}

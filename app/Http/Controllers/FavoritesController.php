<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\restaurant_favorites;

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
}

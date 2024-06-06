<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories\Category;
use App\Models\restaurant_categories;
use Illuminate\Support\Facades\DB;

class Categories extends Controller
{
    //
    public function insertCategory(Request $request)
    {
        $category = new restaurant_categories();
        $category->restaurant_id = $request->input('restaurant_id');
        $category->category_id = $request->input('category_id');
        $category->save();

        return $category;
    }

    public function getCategory()
    {
        $category = DB::table('categories')->get();
        return response()->json($category);
    }
}

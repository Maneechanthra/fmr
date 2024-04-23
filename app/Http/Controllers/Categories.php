<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories\Category;
use App\Models\restaurant_categories;

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
}

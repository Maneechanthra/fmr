<?php

namespace App\Http\Controllers;

use App\Models\restaurant_views;
use Illuminate\Http\Request;

class ViewsController extends Controller
{
    public function insertView(Request $request)
    {
        $view = new restaurant_views;
        $view->restaurant_id = $request->input('restaurant_id');
        $view->view_by = $request->input('view_by');
        $view->save();
        return $view;
    }
}

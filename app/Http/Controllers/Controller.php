<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;





class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    private function storeImage($image)
    {
        if ($image) {
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/restaurants', $imageName);
            return $imageName;
        }

        return null;
    }

    private function storeImageReview($image)
    {
        if ($image) {
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/reviews', $imageName);
            return $imageName;
        }

        return null;
    }
}

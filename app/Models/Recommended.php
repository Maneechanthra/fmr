<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommended extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_name',
        'telephone_1',
        'path'
    ];
}

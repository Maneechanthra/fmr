<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class restaurants extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'restaurant_name',
        'telephone_1',
        'telephone_2',
        'address',
        'latitude',
        'longitude',
    ];
}

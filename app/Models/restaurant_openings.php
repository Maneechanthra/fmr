<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class restaurant_openings extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'day_open',
        'time_open',
        'time_close'
    ];
}

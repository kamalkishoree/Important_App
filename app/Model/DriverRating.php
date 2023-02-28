<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverRating extends Model
{
    protected $fillable = ['order_id', 'driver_id', 'rating','review','rating_type_id'];
}

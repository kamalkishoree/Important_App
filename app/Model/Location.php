<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['latitude','longitude','short_name','address','post_code','created_at'];

    
}

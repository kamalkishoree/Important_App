<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverHomeAddress extends Model
{
    protected $table = 'agents_home_address';

    protected $fillable = ['agent_id', 'latitude', 'longitude','short_name','address','post_code','status'];

   
}
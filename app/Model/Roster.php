<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = ['order_id','driver_id','notification_time','type'];
}

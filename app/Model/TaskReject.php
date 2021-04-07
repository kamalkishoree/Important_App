<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskReject extends Model
{
    protected $fillable = ['order_id','driver_id','status'];
}

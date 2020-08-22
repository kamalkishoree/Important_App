<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NotificationEvent extends Model
{
    protected $fillable = ['notification_type_id','name'];
}

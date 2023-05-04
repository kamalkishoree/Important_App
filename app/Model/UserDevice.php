<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserDevice extends Model
{
    use Notifiable;
    protected $fillable = ['user_id','device_type','device_token','access_token', 'is_vendor_app'];
    
    public function routeNotificationForFcm()
    {
        return $this->device_token;
    }
    
}

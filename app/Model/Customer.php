<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'address', 'phone_number','status'
    ];
    public function location(){
        return $this->hasMany('App\Model\Location', 'created_at', 'id');
        
    }
}
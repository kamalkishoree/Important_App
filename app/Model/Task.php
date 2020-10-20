<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name','from_address','to_address','status','priority','expected_delivery_date'
    ];
}

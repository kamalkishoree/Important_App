<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderAdditionData extends Model
{
    //
    protected $fillable = [
        'order_id',
        'key_name',
        'key_value',
        'description'
      ];
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderFormAttribute extends Model
{
    protected $fillable = ['order_id', 'is_active', 'attribute_id', 'attribute_option_id', 'key_name', 'key_value'];
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderFormAttribute extends Model
{
    protected $fillable = ['order_id', 'is_active', 'attribute_id', 'attribute_option_id', 'key_name', 'key_value'];

    public function attributeOption(){
        return $this->hasOne('App\Model\FormAttributeOption', 'id', 'attribute_option_id');
    }

    public function attribute() {
        return $this->hasOne('App\Model\FormAttribute', 'id', 'attribute_id');
    }
}

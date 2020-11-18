<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id','scheduled_date_time','recipient_phone','Recipient_email','task_description','images_array','auto_alloction','driver_id','key_value_set'];

    public function customer(){
        return $this->hasOne('App\Model\Customer', 'id', 'customer_id');
        
    }
}

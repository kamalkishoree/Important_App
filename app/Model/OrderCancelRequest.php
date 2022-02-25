<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Storage;

class OrderCancelRequest extends Model
{
    // protected $fillable = [
    //     'order_id', 'dependent_task_id', 'task_type_id', 'location_id', 'appointment_duration', 'pricing_rule_id', 'distance', 'assigned_time', 'accepted_time', 'declined_time', 'started_time', 'reached_time', 'failed_time', 'cancelled_time', 'cancelled_by_admin_id', 'Completed_time', 'allocation_type','task_status',
    //     'created_at','note','proof_image','proof_signature','barcode','quantity', 'alcoholic_item'
    // ];

    public function order(){
        return $this->belongsTo('App\Model\Order', 'order_id', 'id');
    }

    public function agent(){
        return $this->belongsTo('App\Model\Agent', 'driver_id', 'id');
    }

}

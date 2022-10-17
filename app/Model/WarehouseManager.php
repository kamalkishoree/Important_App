<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseManager extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'email', 'phone_number', 'status'];

    public function warehouse(){
        return $this->belongsToMany('App\Model\Warehouse', 'warehouse_manager_relation', 'manager_id')->withTimestamps();
    }
}

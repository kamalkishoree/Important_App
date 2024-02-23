<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OrderPanelDetail extends Model
{
    protected $fillable = ['name', 'url', 'code', 'key', 'status', 'last_sync', 'sync_status','type','token','is_approved'];
    
    public static function getOrderData($type)
    {
        return OrderPanelDetail::where([
            'type' => $type
        ])->paginate(10);
    }
}

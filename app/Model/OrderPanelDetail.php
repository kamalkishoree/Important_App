<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OrderPanelDetail extends Model
{
    protected $fillable = ['name', 'url', 'code', 'key', 'status', 'last_sync', 'sync_status'];
}

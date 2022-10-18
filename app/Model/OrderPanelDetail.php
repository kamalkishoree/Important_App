<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderPanelDetail extends Model
{
    protected $fillable = ['db_host', 'db_port', 'db_name', 'db_username', 'db_password', 'is_active'];
}

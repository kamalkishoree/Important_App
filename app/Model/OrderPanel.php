<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPanel extends Model
{
    use HasFactory;

    protected $table = 'order_panel';

    protected $fillable = ['name', 'url', 'code', 'key', 'status'];
}

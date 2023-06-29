<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'payments';
    protected $fillable = ['amount','transaction_id','balance_transaction','type','date','cart_id','order_id','payment_from','payment_option_id','user_id','viva_order_id'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->withTrashed();
    }
}

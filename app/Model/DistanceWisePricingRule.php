<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DistanceWisePricingRule extends Model
{
    protected $table = 'distance_wise_pricing';
    protected $fillable = [
        'price_rule_id',
        'distance_fee',
        'duration_price'
    ];

}

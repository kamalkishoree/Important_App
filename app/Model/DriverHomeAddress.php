<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverHomeAddress extends Model
{
    protected $table = 'agents_home_address';

    protected $fillable = ['agent_id', 'latitude', 'longitude','short_name','address','post_code','status','is_default'];

    public static function unsetDefaultAddress($agent_id){
        DriverHomeAddress::where('agent_id',$agent_id)->update(['is_default' => 0]);
    }
   
}
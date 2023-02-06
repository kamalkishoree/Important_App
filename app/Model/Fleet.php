<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fleet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'registration_name', 'name', 'make', 'model', 'color', 'user_id', 'year'];

    public function getDriver()
    {
        return $this->belongsToMany('App\Model\Agent', 'agent_fleets', 'fleet_id', 'agent_id');
    }
    
}

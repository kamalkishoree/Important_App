<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'manager_id',
        'client_id',
        'location_accuracy',
        'location_frequency'
    ];

    protected $attributes = [
        'client_id'=> null
    ];


    public function client(){
        return $this->belongsTo('App\Model\Client');
    }

    public function manager(){
        return $this->belongsTo('App\Model\Agent');
    }

    public function tags(){
        return $this->belongsToMany('App\Model\Tag', 'team_tags');
    }

    public function agents(){
        return $this->hasMany('App\Model\Agent');
    }
}

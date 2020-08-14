<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function manager(){
        return $this->belongsTo('App\Model\Client');
    }

    public function tags(){
        return $this->belongsToMany('App\Model\Tag', 'team_tags');
    }

    public function agents(){
        return $this->hasMany('App\Model\Agent');
    }
}

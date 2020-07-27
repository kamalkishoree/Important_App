<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function manager(){
        return $this->belongsTo('App\Model\Client');
    }
}

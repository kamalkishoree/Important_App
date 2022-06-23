<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class priceRuleTag extends Model
{
    protected $fillable = ['pricing_rule_id', 'tag_id', 'identity'];

    public function priceRule(){
        return $this->belongsTo('App\Model\PricingRule','pricing_rule_id','id');
    }

    public function team(){
        return $this->belongsTo('App\Model\Team', 'tag_id', 'id')->where('identity', '=', 'Team');
    }

    public function tagsForAgent(){
        return $this->belongsTo('App\Model\TagsForAgent','driver_tag_id','id')->where('identity', '=', 'Agent');
    }

    public function geoFence(){
        return $this->belongsTo('App\Model\Geo','driver_tag_id','id')->where('identity', '=', 'Geo');
    }

    public function tagsForTeam(){
        return $this->belongsTo('App\Model\TagsForTeam','driver_tag_id','id')->where('identity', '=', 'Team_tag');
    }
}

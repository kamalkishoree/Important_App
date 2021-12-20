<?php

namespace App\Model;

use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\WalletFloat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Exception;
use Thumbor\Url;


class Agent extends Authenticatable implements  Wallet, WalletFloat
{
	use Notifiable;
    use HasWallet;
    use HasWalletFloat;

    protected $fillable = [
        'team_id', 'name', 'profile_picture', 'type', 'vehicle_type_id', 'make_model', 'plate_number', 'phone_number', 'color', 'is_activated', 'is_available','cash_at_hand','uid', 'is_approved'
    ];

    protected $appends = ['image_url'];
    
    
    public function getImageUrlAttribute()
    {
        $secret = '';
        $server = 'http://192.168.100.211:8888';
        //$new    = \Thumbor\Url\Builder::construct($server, $secret, 'http://images.example.com/llamas.jpg')->fitIn(90,50);
        return    \Storage::disk("s3")->url($this->profile_picture);
        ///return $new; 

    }

    // public function build()
    // {
    //     return new Url(
    //         $this->server,
    //         $this->secret,
    //         $this->original,
    //         $this->commands->toArray()
    //     );
    // }
   

    public function team(){
       return $this->belongsTo('App\Model\Team')->select("id", "name", "location_accuracy", "location_frequency"); 
    }

    public function logs(){
        return $this->hasOne('App\Model\AgentLog' , 'agent_id','id')->select("id", "agent_id", "lat", "long"); 
    }

    public function vehicle_type(){
        return $this->belongsTo('App\Model\VehicleType'); 
    }

    public function geoFence(){
        return $this->hasMany('App\Model\DriverGeo' , 'driver_id', 'id')->select('driver_id', 'geo_id'); 
    }

    public function order(){
        return $this->hasMany('App\Model\Order','driver_id', 'id');
    }
    public function agentPayment(){
        return $this->hasMany('App\Model\AgentPayment','driver_id', 'id');
    }
    public function agentlog(){
        return $this->hasOne('App\Model\AgentLog','agent_id', 'id')->latest();
    }

    public function tags(){
        return $this->belongsToMany('App\Model\TagsForAgent', 'agents_tags','agent_id','tag_id');
    }

    public function agentfirstlog(){
        return $this->hasOne('App\Model\AgentLog','agent_id', 'id');
    }

    public function agentBankDetails(){
        return $this->hasMany('App\Model\AgentBankDetail' , 'id', 'agent_id');
    }

}
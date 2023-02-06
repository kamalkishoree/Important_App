<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientPreferenceAdditional extends Model
{
  protected $table = 'client_preference_additional';
    protected $fillable = [
      'client_code',
      'key_name',
      'key_value',
      'description',
      'is_active',
      'is_private',
      'is_boolean'
    ];

    public function domain()
    {
      return $this->belongsTo('App\Models\Client','client_code','code')->select('id', 'code', 'custom_domain');
    }
   

    public function client_detail()
    {
      return $this->belongsTo('App\Models\Client','client_code','code');
    }

    public function client_preference()
    {
      return $this->belongsTo('App\Models\Client','client_code','client_code');
    }

}
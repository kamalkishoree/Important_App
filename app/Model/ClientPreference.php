<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientPreference extends Model
{
    protected $fillable = [
        'client_id', 'theme', 'distance_unit', 'currency_id', 'language_id', 'agent_name', 'acknowledgement_type', 'date_format', 'time_format', 'map_type', 'map_key_1', 'map_key_2', 'sms_provider', 'sms_provider_key_1', 'sms_provider_key_2', 'allow_feedback_tracking_url', 'task_type', 'order_id', 'email_plan', 'domain_name', 'personal_access_token_v1', 'personal_access_token_v2','sms_provider_number','allow_all_location'];

    public function currency(){
        return $this->hasOne('App\Model\Currency','id','currency_id');
    }
}
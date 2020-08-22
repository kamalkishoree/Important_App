<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ClientNotification;
class NotificationEvent extends Model
{
    protected $fillable = ['notification_type_id','name'];

    public function is_checked_sms($client_id){
        return ClientNotification::where('notification_event_id',$this->id)->where('client_id',$client_id)->where('request_recieved_sms',1)->count();
    }

    public function is_checked_email($client_id){
        return ClientNotification::where('notification_event_id',$this->id)->where('client_id',$client_id)->where('request_received_email',1)->count();
    }

    public function is_checked_webhook($client_id){
        return ClientNotification::where('notification_event_id',$this->id)->where('client_id',$client_id)->where('request_recieved_webhook',1)->count();
    }

    public function get_client_webhook_url($client_id){
        return ClientNotification::where('notification_event_id',$this->id)->where('client_id',$client_id)->first();
    }
}

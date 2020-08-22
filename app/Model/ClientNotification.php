<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientNotification extends Model
{
    protected $fillable = ['notification_event_id','client_id','webhook_url','request_recieved_sms','request_recieved_email','request_recieved_webhook'];
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Model\Client;
use App\Model\Order;
use Illuminate\Queue\SerializesModels;
use App\Traits\googleMapApiFunctions;
use Log;

class loadDashboardData implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, googleMapApiFunctions;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $order_data = [];
    public $order_id;
    public $client_code;
    public function __construct($orderid)
    {
        $order_data  = Order::select('id', 'status', 'order_time')->where('id', $orderid)->first();
        $this->order_id  = $orderid;
        if(!empty($order_data)):
            $order_data->order_date = date('Y-m-d', strtotime($order_data->order_time));
        endif;
        
        $this->order_data = (!empty($order_data))?$order_data->toArray():[];
        $client_details    = Client::first();
        $this->client_code = $client_details->code;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('orderdata.'.$this->client_code);
    }

    
}

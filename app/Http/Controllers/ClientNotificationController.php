<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\NotificationType;
use App\Model\NotificationEvent;
use App\Model\ClientNotification;

class ClientNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notification_types = NotificationType::with('notification_events')->get();
        $client_notifications = ClientNotification::where('client_id',auth()->user()->id)->get(); 
        return view('notifications')->with([
            'client_notifications' => $client_notifications,
            'notification_types'   => $notification_types
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

        /**
     * Validation method for clients Update 
    */
    protected function updateClientEventValidator(array $data)
    {
        return Validator::make($data, [
            'notification_event_id' => ['required'],
            'current_value' => ['required'],
            'notification_type'=>['required']
        ]);
    }

    public function updateClientNotificationEvent(Request $request){

        $validator = $this->updateClientEventValidator($request->all())->validate();

        switch ($request->notification_type) {
            case 'sms':
                $update = ['request_recieved_sms'=>$request->current_value];
            break;
            case 'email':
                $update = ['request_received_email'=>$request->current_value];
            break;
            case 'webhook':
                $update = ['request_recieved_webhook'=>$request->current_value];
            break;
            default:
                # code...
                break;
        }

        ClientNotification::updateOrCreate([
            'client_id' => auth()->user()->id,
            'notification_event_id' => $request->notification_event_id
        ],$update);

        return response()->json([
            'status'=>'success',
            'message' => 'Updated Successfully',
            'data' => ''
        ]);

    }
}

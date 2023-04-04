<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{OrderPanelDetail};

class orderPanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Order_panel_Data = [];
        if(checkTableExists('warehouses')){
            $Order_panel_Data = OrderPanelDetail::paginate(10);
        }
        return view('order-panel-db-detail.index')->with(['Order_panel_Data' => $Order_panel_Data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      
        if(checkTableExists('order_panel_details')){
            $data = [
                "name"                   => $request->input('name'),
                "url"                 => $request->input('url'),
                "code"              => $request->input('code'),
                "key"           => $request->input('key'),
                "status"             => $request->input('status'),
                "type"             => $request->input('type')
            ];

            $updateOrCreate = OrderPanelDetail::updateOrCreate(['id' => $request->order_panel_id],$data);
            // $OrderPanelDetail = new OrderPanelDetail;
            // $OrderPanelDetail->name = $request->input('name');
            // $OrderPanelDetail->url = $request->input('url');
            // $OrderPanelDetail->code = $request->input('code');
            // $OrderPanelDetail->key = $request->input('key');
            // $OrderPanelDetail->status = $request->input('status');
            // $OrderPanelDetail->save();
        }else{
            return redirect()->route('order-panel-db.index')->with('error','Something went wrong. please try again.');
        }
        return redirect()->route('order-panel-db.index')->with('success','Order Panel Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrderPanelDetail  $orderPanelDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function checkSyncStatus(Request $request){
        $sync_status = $request->sync_status;
        $order_panel_id = $request->order_panel_id;
        $order_panel_data = OrderPanelDetail::find($order_panel_id);
        return \Response::json($order_panel_data->sync_status);
    }
}

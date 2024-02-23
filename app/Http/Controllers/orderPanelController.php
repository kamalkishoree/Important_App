<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ {
    OrderPanelDetail
};

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
        if (checkTableExists('warehouses')) {
            $Order_panel_Data = OrderPanelDetail::getOrderData(0);
        }
        return view('order-panel-db-detail.index')->with([
            'Order_panel_Data' => $Order_panel_Data
        ]);
    }

    public function inventoryIndex(Request $request)
    {
        

        $Order_panel_Data = [];
        if (checkTableExists('warehouses')) {
            $Order_panel_Data = OrderPanelDetail::getOrderData(1);
            
        }
        return view('order-panel-db-detail.index')->with([
            'Order_panel_Data' => $Order_panel_Data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (checkTableExists('order_panel_details')) {

            $secret = $request->url . $request->key.rand(0,999);
            $hashKey = hash('sha256', $secret);
            $data = [
                "name" => $request->input('name'),
                "url" => $request->input('url'),
                "code" => $request->input('code'),
                "key" => $request->input('key'),
                "status" => $request->input('status'),
                "type" => $request->input('type'),
                'token' => $hashKey
            ];

            $request->session()->put('hashKey', $hashKey);
            $request->session()->put('showModal', true);

            // Replace this with your own secret key

            
            $updateOrCreate = OrderPanelDetail::updateOrCreate([
                'id' => $request->order_panel_id
            ], $data);
            // $OrderPanelDetail = new OrderPanelDetail;
            // $OrderPanelDetail->name = $request->input('name');
            // $OrderPanelDetail->url = $request->input('url');
            // $OrderPanelDetail->code = $request->input('code');
            // $OrderPanelDetail->key = $request->input('key');
            // $OrderPanelDetail->status = $request->input('status');
            // $OrderPanelDetail->save();
        } else {
            return redirect()->route('order-panel-db.index')->with('error', 'Something went wrong. please try again.');
        }
        if ($request->type == 1) {
            return redirect()->route('inventory-panel-db')
                ->with('hashKey', $hashKey)
                ->with('showModal', true)
                ->with('success', 'Inventory Panel Added Successfully');
        } else {
            return redirect()->route('order-panel-db.index')
                ->with('hashKey', $hashKey)
                ->with('success', 'Order Panel Added Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\OrderPanelDetail $orderPanelDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain='',$id)
    {
        $order_panel = OrderPanelDetail::where('id',$id)->delete();
          
       
      
       return redirect()->back()->with('success', 'Inventory Details Deleted Successfully');
    }

    public function checkSyncStatus(Request $request)
    {
        $sync_status = $request->sync_status;
        $order_panel_id = $request->order_panel_id;
        $order_panel_data = OrderPanelDetail::find($order_panel_id);
        return \Response::json($order_panel_data->sync_status);
    }
}

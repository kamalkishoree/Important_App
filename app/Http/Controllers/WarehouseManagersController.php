<?php

namespace App\Http\Controllers;

use App\Model\{WarehouseManager, Warehouse};
use Illuminate\Http\Request;
use App\Http\Requests\{AddWarehouseManagerRequest};
class WarehouseManagersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouse_manager = WarehouseManager::with(['warehouse'])->where('status', 1)->orderBy('id', 'DESC')->paginate(10);
        return view('warehouse-manager.index')->with(['warehouse_manager' => $warehouse_manager]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        return view('warehouse-manager.form')->with(['warehouses' => $warehouses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddWarehouseManagerRequest $request)
    {
        $warehouse_manager = new WarehouseManager;
        $warehouse_manager->name = $request->input('name');
        $warehouse_manager->email = $request->input('email');
        $warehouse_manager->phone_number = $request->input('full_number');
        $warehouse_manager->status = $request->input('status');
        $warehouse_manager->save();
        $warehouses = $request->input('warehouses');
        $warehouse_manager->warehouse()->sync($warehouses);
        return redirect()->route('warehouse-manager.index')->with('success','Warehouse Manager Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\warehouse_managers  $warehouse_managers
     * @return \Illuminate\Http\Response
     */
    public function edit($port, WarehouseManager $WarehouseManager)
    {
        $warehouses = Warehouse::all();
        return view('warehouse-manager.form')->with(['warehouses' => $warehouses, 'WarehouseManager' => $WarehouseManager]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\warehouse_managers  $warehouse_managers
     * @return \Illuminate\Http\Response
     */
    public function update($port, AddWarehouseManagerRequest $request, WarehouseManager $WarehouseManager)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('full_number'),
            'status' => $request->input('status')
        ];
        $WarehouseManager->update($data);
        $warehouses = $request->input('warehouses');
        $WarehouseManager->warehouse()->sync($warehouses);
        return redirect()->route('warehouse-manager.index')->with('success','Warehouse Manager Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\warehouse_managers  $warehouse_managers
     * @return \Illuminate\Http\Response
     */
    public function destroy($port, WarehouseManager $WarehouseManager)
    {
        $WarehouseManager->delete();
        return redirect()->back()->with('success','Warehouse Manager Deleted Successfully');
    }
}

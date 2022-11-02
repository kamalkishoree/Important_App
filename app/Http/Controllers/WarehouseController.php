<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Warehouse, Amenities, Category, Client};
use App\Http\Requests\{AddWarehouseRequest};
use Auth;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $warehouses = Warehouse::with(['amenity', 'category'])->orderBy('id', 'DESC');
        $managerWarehouses = Client::with('warehouse')->where('id', $user->id)->first();
        $managerWarehousesIds = $managerWarehouses->warehouse->pluck('id'); 
        if($user->is_superadmin == 0 && $user->manager_type == 1){
            $warehouses = $warehouses->whereIn('id', $managerWarehousesIds);
        }
        $warehouses = $warehouses->paginate(10);
        return view('warehouse.index')->with(['warehouses' => $warehouses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenities = Amenities::all();
        $category = Category::where('status', 1)->get();
        return view('warehouse.form')->with(['amenities' => $amenities, 'category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddWarehouseRequest $request)
    {
        $warehouse = new Warehouse;
        $warehouse->name = $request->input('name');
        $warehouse->code = $request->input('code');
        $warehouse->address = $request->input('address');
        $warehouse->latitude = $request->input('latitude');
        $warehouse->longitude = $request->input('longitude');
        $warehouse->save();
        $amenities = $request->input('amenities');
        $warehouse->amenity()->sync($amenities);
        $category = $request->input('category');
        $warehouse->category()->sync($category);
        return redirect()->route('warehouse.index')->with('success','Warehouse Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit($port, Warehouse $warehouse)
    {
        $amenities = Amenities::all();
        $category = Category::where('status', 1)->get();
        return view('warehouse.form')->with(['amenities' => $amenities, 'warehouse' => $warehouse, 'category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update($port, AddWarehouseRequest $request, Warehouse $warehouse)
    {
        $data = [
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'address' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude')
        ];
        $warehouse->update($data);
        $amenities = $request->input('amenities');
        $warehouse->amenity()->sync($amenities);
        $category = $request->input('category');
        $warehouse->category()->sync($category);
        return redirect()->route('warehouse.index')->with('success','Warehouse Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($port, Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->back()->with('success','Warehouse Deleted Successfully');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Warehouse, Amenities, Category};
use App\Http\Requests\{AddWarehouseRequest};

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::with(['amenity', 'category'])->orderBy('id', 'DESC')->paginate(10);
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
        $warehouse->category_id = $request->input('category');
        $warehouse->save();
        $amenities = $request->input('amenities');
        $warehouse->amenity()->sync($amenities);
        return redirect()->back()->with('success','Warehouse Added Successfully');
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
            'category_id' => $request->input('category')
        ];
        $warehouse->update($data);
        $amenities = $request->input('amenities');
        $warehouse->amenity()->sync($amenities);
        return redirect()->back()->with('success','Warehouse Updated Successfully');
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

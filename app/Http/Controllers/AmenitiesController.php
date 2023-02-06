<?php

namespace App\Http\Controllers;

use App\Model\Amenities;
use Illuminate\Http\Request;

class AmenitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if(checkTableExists('amenities')){
            $amenities = new Amenities;
            $amenities->name = $request->input('name');
            $amenities->save();
        }
        return redirect()->back()->with('success','Amenities Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function show(Amenities $amenities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function edit(Amenities $amenities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Amenities $amenities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Amenities  $amenities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Amenities $amenities)
    {
        //
    }
}

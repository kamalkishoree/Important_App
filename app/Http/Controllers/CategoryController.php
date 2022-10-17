<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('id', 'DESC')->paginate(10);
        return view('category.index')->with(['category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Category::updateOrCreate(
			['id'=> $request->cat_id], 
			[
				'name' => $request->input('name'),
				'status' => $request->input('status')
            ]
        );
        if($request->cat_id != ''){
            return redirect()->back()->with('success','Category Updated Successfully');
        }
        return redirect()->back()->with('success','Category Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($port, Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success','Category Deleted Successfully');
    }
}

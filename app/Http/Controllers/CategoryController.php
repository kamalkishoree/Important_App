<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Category,CategoryTranslation};

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
        $check_category = Category::where('slug', $request->name)->first();
        if(empty($check_category)){
            Category::updateOrCreate(
                ['id'=> $request->cat_id], 
                [
                    'slug' => $request->input('name'),
                    'type_id' => 1,
                    'is_visible' => 1,
                    'status' => $request->input('status')
                ]
            );
            CategoryTranslation::updateOrCreate(
                ['category_id'=> $request->cat_id], 
                [
                    'name' => $request->input('name')
                ]
            );
            if($request->cat_id == ''){
                return redirect()->back()->with('success','Category Added Successfully.');
            }else{
                return redirect()->back()->with('success','Category Updated Successfully.');
            }
        }else{
            return redirect()->back()->with('error','Category Already Exist.');
        }
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

    public function importOrderSideCategory(){
        $db_connection = connect_with_order_panel();
        $order_category = $db_connection->table('categories')->select(['categories.*','category_translations.*','category_translations.trans-slug as trans_slug'])->join('category_translations', 'category_translations.category_id', '=','categories.id')->where('category_translations.language_id', 1)->get();
        foreach($order_category as $category){
            $data = [
                'icon' => $category->icon,
                'slug' => $category->slug,
                'type_id' => $category->type_id,
                'image' => $category->image,
                'is_visible' => $category->is_visible,
                'status' => $category->status,
                'position' => $category->position,
                'is_core' => $category->is_core,
                'can_add_products' => $category->can_add_products,
                'parent_id' => $category->parent_id,
                'vendor_id' => $category->vendor_id,
                'client_code' => $category->client_code,
                'display_mode' => $category->display_mode,
                'show_wishlist' => $category->show_wishlist,
                'sub_cat_banners' => $category->sub_cat_banners,
                'royo_order_category_id' => $category->id
            ];
            $categorySave = Category::updateOrCreate([ 'slug' => $category->slug ], $data);
            
            $transl_data = [
                'name' => $category->name,
                'trans-slug' => $category->trans_slug,
                'meta_title' => $category->meta_title,
                'meta_description' => $category->meta_description,
                'meta_keywords' => $category->meta_keywords,
                'category_id' => $categorySave->id,
                'language_id' => $category->language_id
            ];
            $categoryTransSave = CategoryTranslation::updateOrCreate([ 'category_id' => $categorySave->id ], $transl_data);
        }
        return redirect()->back()->with('success','Category Imported Successfully');
    }
}

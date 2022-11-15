<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Category,CategoryTranslation,Product,ProductVariant,ProductCategories,ProductTranslation, ClientPreference, Client, OrderPanelDetail};
use App\Model\Order\{Category as ROCategory};
use Illuminate\Support\Facades\Http;
use App\Model\Customer;
use DataTables;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(REQUEST $request)
    {
        $category = [];
        $product_category = [];
        $sku_url = '';
        $order_panel_id = $request->input('order_panel_id');
        $order_panel = [];
        if(@$order_panel_id && $order_panel_id!='null'){
            $order_panel = OrderPanelDetail::find($order_panel_id);
            if($order_panel->sync_status == 2){
                $orderpanel = OrderPanelDetail::find($order_panel_id);
                $orderpanel->sync_status = 0;
                $orderpanel->save();
            }
        }
    
        if(checkTableExists('categories')){
            $category = Category::with('products')->orderBy('id', 'DESC')->paginate(10);
            if(checkColumnExists('categories', 'order_panel_id')){
                if($order_panel_id != "all" && $order_panel_id != null){
                    $category = Category::with('products')->where('order_panel_id', $order_panel_id)->orderBy('id', 'DESC')->paginate(10);
                }
            }
            // dd($category);
            $product_category = Category::orderBy('id', 'DESC')->get();
            $client_preferences = ClientPreference::first();
        
            $client = Client::orderBy('id','asc')->first();
            if(isset($client->custom_domain) && !empty($client->custom_domain) && $client->custom_domain != $client->sub_domain)
            $sku_url =  ($client->custom_domain);
            else
            $sku_url =  ($client->sub_domain.env('SUBMAINDOMAIN'));

            $sku_url = array_reverse(explode('.',$sku_url));
            $sku_url = implode(".",$sku_url);
        }
        $orderDb_detail = OrderPanelDetail::all();
       
        return view('category.index')->with(['order_panel' => $order_panel,'category' => $category, 'product_category' => $product_category, 'sku_url' => $sku_url, 'order_db_detail' => $orderDb_detail]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check_category = [];
        if($request->cat_id == ''){
            $check_category = [];
            if(checkTableExists('categories')){
                $check_category = Category::where('slug', $request->name)->first();
            }
        }
        if(empty($check_category)){
            if(checkTableExists('categories')){
                Category::updateOrCreate(
                    ['id'=> $request->cat_id], 
                    [
                        'slug' => $request->input('name'),
                        'type_id' => 1,
                        'is_visible' => 1,
                        'status' => $request->input('status')
                    ]
                );
            }
            if(checkTableExists('category_translations')){
                CategoryTranslation::updateOrCreate(
                    ['category_id'=> $request->cat_id], 
                    [
                        'name' => $request->input('name'),
                        'status' => $request->input('status')
                    ]
                );
            }
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
        if(checkTableExists('categories')){
            $category->forceDelete();
        }
        return redirect()->back()->with('success','Category Deleted Successfully');
    }
    

    public function getOrderSideData(Request $request){
        $order_panel_id =  $request->order_panel_id;
        if($order_panel_id != 'all'){
            $order_details = OrderPanelDetail::find($order_panel_id);
            $order_details->sync_status = 0;
            $order_details->save();
            $order_details = OrderPanelDetail::find($order_panel_id);
            $url = $order_details->url;

            // URL
            $apiAuthCheckURL = $url.'/api/v1/dispatcher/check-order-keys';
            
            // POST Data
            $postInput = [
                
            ];
    
            // Headers
            $headers = [
                'shortcode' => $order_details->code,
                'code' => $order_details->code,
                'key' => $order_details->key
            ];
            
            $response = Http::withHeaders($headers)->post($apiAuthCheckURL, $postInput);
    
            // $statusCode = $response->status();
            $checkAuth = json_decode($response->getBody(), true);
            
            if( @$checkAuth['status'] == 200){
                $apiRequestURL = $url.'/api/v1/category-product-sync-dispatcher';
            
                // POST Data
                $postInput = ['order_panel_id' => $order_panel_id];
                $headers['Authorization'] = $checkAuth['token'];
                $response = Http::withHeaders($headers)->post($apiRequestURL, $postInput);
                $responseBody = json_decode($response->getBody(), true);
                // dd($responseBody);
                if( @$responseBody['status'] == 200){
                    $order_details = OrderPanelDetail::find($order_panel_id);
                    $order_details->sync_status = 1;
                    $order_details->save();
                    // dd($responseBody);
                    // $this->importOrderSideCategory($responseBody['data']);
                }elseif( @$responseBody['error'] && !empty($responseBody['error'])){
                    return redirect()->back()->with('error', $responseBody['error']);
                }
             } elseif( @$checkAuth['status'] == 401){
                return redirect()->back()->with('error', $checkAuth['message']);
            }else{
                return redirect()->back()->with('error', 'Invalid Order Panel Url.');    
            }
            return redirect()->back()->with('success', 'Category & Product Import Is Processing.');
        }else{
            return redirect()->back()->with('error', 'Please select order panel DB.');
        } 
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

    public function categoryFilter(Request $request)
    {
        $category = Category::with('products');
        if(checkColumnExists('categories', 'order_panel_id')){
            $order_panel_id = $request->order_panel_id;
            if($order_panel_id != "" && $order_panel_id != null){
                $category = $category->where('order_panel_id', $order_panel_id);
            }
        }
        if (!empty($request->get('search'))) {
            $search = $request->get('search');
            $category = $category->where('slug', 'Like', '%'.$search.'%');
        }
        $category = $category->orderBy('id', 'DESC')->get();
        return Datatables::of($category)
            ->addColumn('name', function ($category) use ($request) {
                $name = !empty($category->slug) ? $category->slug : '';
                return $name;
            })
            ->addColumn('status', function ($category) use ($request) {
                if($category->status == 1){
                    return $status = 'Active';
                }else{
                    return $status = 'InActive';
                }
            })
            ->addColumn('created_at', function ($category) use ($request) {
                $created_at = !empty($category->created_at) ? $category->created_at : '';
                return formattedDate($created_at);
            })
            ->addColumn('total_products', function ($category) use ($request) {
                $total_products = !empty($category->products) ? count($category->products) : '0';
                return $total_products;
            })
            ->addColumn('action', function ($category) use ($request) {
                $action = '<div class="inner-div"> <a href="JavaScript:void(0);"  class="action-icon editIconBtn openEditCategoryModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false" data-name="'.$category->slug.'" data-id="'.$category->id.'" data-status="'.$category->status.'" style="margin-top: 5px;"> <i class="mdi mdi-square-edit-outline"></i></a></div>';
                    
                $action.='<div class="inner-div">
                <form method="POST" action="'.route('category.destroy', $category->id).'">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
                <input type="hidden" name="_method" value="DELETE">
                <div class="form-group">
                <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>
                </div>
                </form>
                </div>';
                return $action;
            })
            ->filter(function ($instance) use ($request) { }, true)
            ->rawColumns(['action'])
            ->make(true);
    }
}

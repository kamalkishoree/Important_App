<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Category,CategoryTranslation,Product,ProductVariant,ProductCategories,ProductTranslation, ClientPreference, Client, OrderPanelDetail};
use Illuminate\Support\Facades\Http;
use App\Model\Customer;
use DataTables;
use Illuminate\Support\Str;

class ServicesController extends Controller
{
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
            $category = Category::with('products')->where('type_id','8')->orderBy('id', 'DESC')->paginate(10);
            if(checkColumnExists('categories', 'order_panel_id')){
                if($order_panel_id != "all" && $order_panel_id != null){
                    $category = Category::where('type_id','8')->where('order_panel_id', $order_panel_id)->orderBy('id', 'DESC')->paginate(10);
                }
            }
      
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
       
        return view('services.index')->with(['order_panel' => $order_panel,'category' => $category, 'product_category' => $product_category, 'sku_url' => $sku_url, 'order_db_detail' => $orderDb_detail]);
    }

    public function servicesFilter(Request $request)
    {
        $category = Category::with('products')->where('type_id','8');
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

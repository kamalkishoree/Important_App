<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;
use App\Model\OrderPanelDetail;
use App\Model\Client;
use Illuminate\Support\Facades\App;
use App\Model\Product;
use App\Model\ProductVariant;

trait inventoryManagement
{

    public function authenticateInventoryPanel()
    {
        $order_panel_details = OrderPanelDetail::where([
            'type' => 1
        ])->first();
        
        if(!empty($order_panel_details)){
            // URL
            $url = $order_panel_details->url;
            $code = $order_panel_details->code;
            $apiAuthCheckURL = $url . '/api/v1/check-dispatch-keys';
    
            // POST Data
            $postInput = [];
    
            // Headers
            $headers = [
                'shortcode' => $code,
    //          'code' => $code,
                'key' => $code
            ];
            $response = Http::withHeaders($headers)->post($apiAuthCheckURL, $postInput);
            $checkAuth = json_decode($response->getBody(), true);
            
            
            if (@$checkAuth['status'] == 200) {
                return $checkAuth['token'];
            }
            // elseif( @$checkAuth['status'] == 401){
            // throw new \ErrorException($checkAuth['message'], 400);
            // }
    
            throw new \ErrorException('Invalid Inventory Panel Url.', 400);
        }
    }

    public function getInventoryPanelDetails($token, $ids, $flag = null)
    {
        $inventory_detail = OrderPanelDetail::where([
            'type' => 1
        ])->first();
        if(!empty($inventory_detail)){
            $url = $inventory_detail->url;
            $code = $inventory_detail->code;
            $apiRequestURL = $url . '/api/v1/get-inventory-panel-detail';
            if (empty($flag)) {
                $ids = json_decode($ids, true);
    
                $ids = array_column($ids, 'product_variant_id');
            }
            $products = ProductVariant::all()->whereIn('id', $ids);
            // POST Data
            $postInput = [
                'product_data' => json_encode($products)
            ];
            $headers = [
                'shortcode' => $code,
    //             'code' => $code,
                'key' => $code
            ];
           
            $headers['Authorization'] = $token;
            $response = Http::withHeaders($headers)->post($apiRequestURL, $postInput);
            $responseBody = json_decode($response->getBody(), true);
    
            if (@$responseBody['status'] == 200) {
                return $responseBody;
            }
        }
        return;
    }
}
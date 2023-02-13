<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Category,CategoryTranslation,Product,ProductVariant,ProductCategories,ProductTranslation, ClientPreference, Client, OrderPanelDetail};

class SyncCategoryProductController extends Controller
{
    protected $order_panel_id;
    protected $order_DB_Name;

    public function SyncCategoryProduct(Request $request){
        // dd($request['data']);
        $order_details = OrderPanelDetail::find($request['order_panel_id']);
        if( ($order_details) && @$request['data'] && count($request['data']) > 0){
            $this->order_panel_id = $request['order_panel_id'];
            $dataBaseName = $request['databaseName'];
            $this->importOrderSideCategory($request['data'],  $dataBaseName);
        }
        $order_details->sync_status = 2;
        $order_details->save();
        return true;
    }
    
    public function importOrderSideCategory($categories,$dataBaseName=''){
        // $categories = ROCategory::with(['translation','products','products.variant','products.translation'])->get();
        foreach($categories as $cat){
            \Log::info( 'categorySave transl_data');
            \Log::info( $cat);
            $category_id = $this->syncSingleCategory($cat,$dataBaseName);
            if(!empty($cat['products']) && count($cat['products']) > 0){
                foreach($cat['products'] as $product){
                    $product_id = $this->syncSingleProduct($category_id, $product,  $dataBaseName);
                    $variantId = $this->syncProductVariant($product_id, $product,  $dataBaseName);
                }
            }
        }
    }

    public function syncSingleProduct($category_id, $product,  $dataBaseName){
        // dd($product['translation']);
        if(checkTableExists('products')){ 
            $product_update_create = [
                "sku"                   => $dataBaseName."_".$product['sku'],
                "title"                 => $product['title'],
                "url_slug"              => $product['url_slug'],
                "description"           => $product['description'],
                "body_html"             => $product['body_html'],
                "vendor_id"             => $product['vendor_id'],
                "type_id"               => $product['type_id'],
                "country_origin_id"     => $product['country_origin_id'],
                "is_new"                => $product['is_new'],
                "is_featured"           => $product['is_featured'],
                "is_live"               => $product['is_live'],
                "is_physical"           => $product['is_physical'],
                "weight"                => $product['weight'],
                "weight_unit"           => $product['weight_unit'],
                "has_inventory"         => $product['has_inventory'],
                "has_variant"           => $product['has_variant'],
                "sell_when_out_of_stock" => $product['sell_when_out_of_stock'],
                "requires_shipping"     => $product['requires_shipping'],
                "Requires_last_mile"    => $product['Requires_last_mile'],
                "averageRating"         => $product['averageRating'],
                "inquiry_only"          => $product['inquiry_only'],
                "publish_at"            => $product['publish_at'],
                "created_at"            => $product['created_at'],
                "updated_at"            => $product['updated_at'],
                // "brand_id"              => $i_product['brand_id'],
                "tax_category_id"       => $product['tax_category_id'] ?? null,
                "deleted_at"            => $product['deleted_at'],
                "pharmacy_check"        => $product['pharmacy_check'],
                "tags"                  => $product['tags'],
                "need_price_from_dispatcher" => $product['need_price_from_dispatcher'],
                "mode_of_service"       => $product['mode_of_service'],
                "delay_order_hrs"       => $product['delay_order_hrs'],
                "delay_order_min"       => $product['delay_order_min'],
                "pickup_delay_order_hrs" => $product['pickup_delay_order_hrs'],
                "pickup_delay_order_min" => $product['pickup_delay_order_min'],
                "dropoff_delay_order_hrs" => $product['dropoff_delay_order_hrs'],
                "dropoff_delay_order_min" => $product['dropoff_delay_order_min'],
                "need_shipment"         => $product['need_shipment'],
                "minimum_order_count"   => $product['minimum_order_count'],
                "batch_count"           => $product['batch_count'],
                "delay_order_hrs_for_dine_in" => $product['delay_order_hrs_for_dine_in'],
                "delay_order_min_for_dine_in" => $product['delay_order_min_for_dine_in'],
                "delay_order_hrs_for_takeway" => $product['delay_order_hrs_for_takeway'],
                "delay_order_min_for_takeway" => $product['delay_order_min_for_takeway'],
                "age_restriction"       => $product['age_restriction'],
                // 'brand_id'              => $product->deleted_at,
                "category_id"           => $category_id,
                //"store_id"              => $vid,
                'order_panel_id' => $this->order_panel_id
            ];
            $productSave = Product::updateOrCreate(['sku' => $dataBaseName."_".$product['sku'], 'order_panel_id' => $this->order_panel_id],$product_update_create);

            foreach(@$product['translation'] as $translation){

                $product_trans = [
                    'title'         => $translation['title'],
                    'body_html'     => $translation['title'],
                    'meta_title'    => $translation['title'],
                    'meta_keyword'  => $translation['title'],
                    'meta_description' => $translation['title'],
                    'product_id'    => $productSave->id,
                    'language_id'   => $translation['language_id'],
                ];

                ProductTranslation::updateOrCreate(['product_id' => $productSave->id],$product_trans);

            }

            // Sync Product Categories
            $data = ['product_id' => $productSave->id, 'category_id' => $category_id ];
            ProductCategories::updateOrCreate(['product_id' => $productSave->id],$product_update_create);
            
            return $productSave->id;
        }else{
            return '';
        }
    }

    public function syncProductVariant($product_id, $product ,  $dataBaseName){
        if(checkTableExists('product_variants')){ 
            $variants = @$product['variant'];
            // # Add product variant
            foreach($variants as $variant) {     # import product variant
                $product_variant = [
                    "sku"           => $dataBaseName."_".$variant['sku'],
                    "title"         => $variant['title'],
                    "quantity"      => $variant['quantity'],
                    "price"         => $variant['price'],
                    "position"      => $variant['position'],
                    "compare_at_price" => $variant['compare_at_price'],
                    "barcode"       => $variant['barcode'],
                    "expiry_date"       => $variant['expiry_date'] ?? null,
                    "cost_price"    => $variant['cost_price'],
                    "currency_id"   => $variant['currency_id'],
                    "tax_category_id" => $variant['tax_category_id'],
                    "inventory_policy" => $variant['inventory_policy'] ?? null,
                    "fulfillment_service" => $variant['fulfillment_service']?? null,
                    "inventory_management" => $variant['inventory_management']?? null,
                    "status"        => $variant['status'] ?? 1,
                    "container_charges" => $variant['container_charges'] ?? '0.0000',
                    "product_id"    => $product_id,
                ];
                $product_variant_import = ProductVariant::updateOrInsert(['sku' => $dataBaseName."_".$variant['sku']],$product_variant);
            }
            return true;
        }else{
            return false;
        }
    }

    public function syncSingleCategory($cat,  $dataBaseName){
        if(checkTableExists('categories')){
            $data = [
                'icon' => $cat['icon']['icon'],
                'slug' => $dataBaseName."_".$cat['slug'],
                'type_id' => $cat['type_id'],
                'image' => $cat['image']['image'],
                'is_visible' => $cat['is_visible'],
                'status' => $cat['status'],
                'position' => $cat['position'],
                'is_core' => $cat['is_core'],
                'can_add_products' => $cat['can_add_products'],
                'parent_id' => $cat['parent_id'],
                'vendor_id' => $cat['vendor_id'],
                'client_code' => $cat['client_code'],
                'display_mode' => $cat['display_mode'],
                'show_wishlist' => $cat['show_wishlist'],
                'sub_cat_banners' => $cat['sub_cat_banners']['sub_cat_banners'] ?? null,
                'royo_order_category_id' => $cat['id'],
                'order_panel_id' => $this->order_panel_id
            ];
            
            $categorySave = Category::updateOrCreate([ 'slug' => $dataBaseName."_".$cat['slug'], 'order_panel_id' => $this->order_panel_id ], $data);
            $transl_data = [
                'name' => $cat['translation']['name'] ?? $cat['slug'],
                'trans-slug' => $cat['translation']['trans_slug'] ?? '',
                'meta_title' => $cat['translation']['meta_title'] ?? '',
                'meta_description' => $cat['translation']['meta_description'] ?? '',
                'meta_keywords' => $cat['translation']['meta_keywords'] ?? '',
                'category_id' => $categorySave->id ?? '',
                'language_id' => 1
            ];
          
            $categoryTransSave = CategoryTranslation::updateOrCreate([ 'category_id' => $categorySave->id ], $transl_data);
            return $categorySave->id;
        }else{
            return '';
        }
    }
}

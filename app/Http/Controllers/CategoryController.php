<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Category,CategoryTranslation,Product,ProductVariant,ProductCategories,ProductTranslation};
use App\Model\Order\{Category as ROCategory};

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
        $check_category = [];
        if($request->cat_id == ''){
            $check_category = Category::where('slug', $request->name)->first();
        }
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
                    'name' => $request->input('name'),
                    'status' => $request->input('status')
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
        $category->forceDelete();
        return redirect()->back()->with('success','Category Deleted Successfully');
    }

    public function importOrderSideCategory(){
        $categories = ROCategory::with(['translation','products','products.variant','products.translation'])->get();
        // dd($categories->toArray());
        foreach($categories as $cat){
            $category_id = $this->syncSingleCategory($cat);
            if(!empty($cat->products) && $cat->products->count() > 0){
                foreach($cat->products as $product){
                    $product_id = $this->syncSingleProduct($category_id, $product);
                    $variantId = $this->syncProductVariant($product_id, $product);
                }
            }
        }
        return redirect()->back()->with('success', 'Category & Product Import Successfully.');
    }

    public function syncSingleProduct($category_id, $product){
        $product_update_create = [
            "sku"                   => $product->sku,
            "title"                 => $product->title,
            "url_slug"              => $product->url_slug,
            "description"           => $product->description,
            "body_html"             => $product->body_html,
            "vendor_id"             => $product->vendor_id,
            "type_id"               => $product->type_id,
            "country_origin_id"     => $product->country_origin_id,
            "is_new"                => $product->is_new,
            "is_featured"           => $product->is_featured,
            "is_live"               => $product->is_live,
            "is_physical"           => $product->is_physical,
            "weight"                => $product->weight,
            "weight_unit"           => $product->weight_unit,
            "has_inventory"         => $product->has_inventory,
            "has_variant"           => $product->has_variant,
            "sell_when_out_of_stock" => $product->sell_when_out_of_stock,
            "requires_shipping"     => $product->requires_shipping,
            "Requires_last_mile"    => $product->Requires_last_mile,
            "averageRating"         => $product->averageRating,
            "inquiry_only"          => $product->inquiry_only,
            "publish_at"            => $product->publish_at,
            "created_at"            => $product->created_at,
            "updated_at"            => $product->updated_at,
            // "brand_id"              => $i_product['brand_id'],
            "tax_category_id"       => $product->tax_category_id ?? null,
            "deleted_at"            => $product->deleted_at,
            "pharmacy_check"        => $product->pharmacy_check,
            "tags"                  => $product->tags,
            "need_price_from_dispatcher" => $product->need_price_from_dispatcher,
            "mode_of_service"       => $product->mode_of_service,
            "delay_order_hrs"       => $product->delay_order_hrs,
            "delay_order_min"       => $product->delay_order_min,
            "pickup_delay_order_hrs" => $product->pickup_delay_order_hrs,
            "pickup_delay_order_min" => $product->pickup_delay_order_min,
            "dropoff_delay_order_hrs" => $product->dropoff_delay_order_hrs,
            "dropoff_delay_order_min" => $product->dropoff_delay_order_min,
            "need_shipment"         => $product->need_shipment,
            "minimum_order_count"   => $product->minimum_order_count,
            "batch_count"           => $product->batch_count,
            "delay_order_hrs_for_dine_in" => $product->delay_order_hrs_for_dine_in,
            "delay_order_min_for_dine_in" => $product->delay_order_min_for_dine_in,
            "delay_order_hrs_for_takeway" => $product->delay_order_hrs_for_takeway,
            "delay_order_min_for_takeway" => $product->delay_order_min_for_takeway,
            "age_restriction"       => $product->age_restriction,
            // 'brand_id'              => $product->deleted_at,
            "category_id"           => $category_id,
            //"store_id"              => $vid,
        ];
        $productSave = Product::updateOrCreate(['sku' => $product->sku],$product_update_create);

        $product_trans = [
            'title'         => $product->translation->title,
            'body_html'     => $product->translation->title,
            'meta_title'    => $product->translation->title,
            'meta_keyword'  => $product->translation->title,
            'meta_description' => $product->translation->title,
            'product_id'    => $productSave->id,
            'language_id'   => $product->translation->language_id,
        ];

        ProductTranslation::updateOrCreate(['product_id' => $productSave->id],$product_trans);

        // Sync Product Categories
        $data = ['product_id' => $productSave->id, 'category_id' => $category_id ];
        ProductCategories::updateOrCreate(['product_id' => $productSave->id],$product_update_create);
        
        return $productSave->id;
    }

    public function syncProductVariant($product_id, $product){
        $variants = $product['variant'];
        // # Add product variant
        foreach($variants as $variant) {     # import product variant
            $product_variant = [
                "sku"           => $variant->sku,
                "title"         => $variant->title,
                "quantity"      => $variant->quantity,
                "price"         => $variant->price,
                "position"      => $variant->position,
                "compare_at_price" => $variant->compare_at_price,
                "barcode"       => $variant->barcode,
                "expiry_date"       => $variant->expiry_date,
                "cost_price"    => $variant->cost_price,
                "currency_id"   => $variant->currency_id,
                "tax_category_id" => $variant->tax_category_id,
                "inventory_policy" => $variant->inventory_policy,
                "fulfillment_service" => $variant->fulfillment_service,
                "inventory_management" => $variant->inventory_management,
                "status"        => $variant->status ?? 1,
                "container_charges" => $variant->container_charges ?? '0.0000',
                "product_id"    => $product_id,
            ];
            $product_variant_import = ProductVariant::updateOrInsert(['sku' => $variant['sku']],$product_variant);
        }
        return true;
    }

    public function syncSingleCategory($cat){
        $data = [
            'icon' => $cat->icon,
            'slug' => $cat->slug,
            'type_id' => $cat->type_id,
            'image' => $cat->image,
            'is_visible' => $cat->is_visible,
            'status' => $cat->status,
            'position' => $cat->position,
            'is_core' => $cat->is_core,
            'can_add_products' => $cat->can_add_products,
            'parent_id' => $cat->parent_id,
            'vendor_id' => $cat->vendor_id,
            'client_code' => $cat->client_code,
            'display_mode' => $cat->display_mode,
            'show_wishlist' => $cat->show_wishlist,
            'sub_cat_banners' => $cat->sub_cat_banners,
            'royo_order_category_id' => $cat->id
        ];
        $categorySave = Category::updateOrCreate([ 'slug' => $cat->slug ], $data);
        $transl_data = [
            'name' => $cat->translation->name ?? $cat->slug,
            'trans-slug' => $cat->translation->trans_slug ?? '',
            'meta_title' => $cat->translation->meta_title ?? '',
            'meta_description' => $cat->translation->meta_description ?? '',
            'meta_keywords' => $cat->translation->meta_keywords ?? '',
            'category_id' => $categorySave->id ?? '',
            'language_id' => 1
        ];
        $categoryTransSave = CategoryTranslation::updateOrCreate([ 'category_id' => $categorySave->id ], $transl_data);
        return $categorySave->id;
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'title', 'url_slug', 'description', 'body_html', 'vendor_id', 'category_id', 'type_id', 'country_origin_id', 'is_new', 'is_featured', 'is_live', 'is_physical', 'weight', 'weight_unit', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'publish_at', 'inquiry_only','has_variant','averageRating','tags', 'pharmacy_check', 'deleted_at', 'celebrity_id', 'brand_id', 'tax_category_id','need_price_from_dispatcher','mode_of_service','delay_order_hrs','delay_order_min','pickup_delay_order_hrs','pickup_delay_order_min','dropoff_delay_order_hrs','dropoff_delay_order_min','minimum_order_count','batch_count','service_charges_tax','delivery_charges_tax','container_charges_tax','fixed_fee_tax','service_charges_tax_id','delivery_charges_tax_id','container_charges_tax_id','fixed_fee_tax_id','global_product_id','import_from_inventory','markup_price','order_panel_id'];

    public function variant(){
        return $this->hasMany('App\Model\ProductVariant')->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'position', 'compare_at_price', 'barcode', 'cost_price', 'currency_id', 'tax_category_id','container_charges')->where('status', 1);
    }
    public function pvariant(){
        return $this->hasOne('App\Model\ProductVariant')->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'position', 'compare_at_price', 'barcode', 'cost_price', 'currency_id', 'tax_category_id','container_charges')->where('status', 1);
    }
   

    public function category(){
        return $this->hasOne('App\Model\ProductCategory')->select('product_id', 'category_id');
    }

    public function translation(){
        return $this->hasOne('App\Model\ProductTranslation','product_id','id');
    }

    public function primary(){
        $langData = $this->hasOne('App\Model\ProductTranslation');
        return $langData;
    }
    
    public function media(){
        return $this->hasMany('App\Model\ProductImage')->select('product_id', 'media_id', 'is_default');
    }
    
    
    
    public function pimage()
    {
        return $this->hasMany('App\Model\ProductImage')->select('product_images.product_id', 'product_images.media_id', 'product_images.is_default', 'vendor_media.media_type', 'vendor_media.path')->join('vendor_media', 'vendor_media.id', 'product_images.media_id')->limit(1);
    }

}

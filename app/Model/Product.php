<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'title', 'url_slug', 'description', 'body_html', 'vendor_id', 'category_id', 'type_id', 'country_origin_id', 'is_new', 'is_featured', 'is_live', 'is_physical', 'weight', 'weight_unit', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'publish_at', 'inquiry_only','has_variant','averageRating','tags', 'pharmacy_check', 'deleted_at', 'celebrity_id', 'brand_id', 'tax_category_id','need_price_from_dispatcher','mode_of_service','delay_order_hrs','delay_order_min','pickup_delay_order_hrs','pickup_delay_order_min','dropoff_delay_order_hrs','dropoff_delay_order_min','minimum_order_count','batch_count','service_charges_tax','delivery_charges_tax','container_charges_tax','fixed_fee_tax','service_charges_tax_id','delivery_charges_tax_id','container_charges_tax_id','fixed_fee_tax_id','global_product_id','import_from_inventory','markup_price','order_panel_id'];
}

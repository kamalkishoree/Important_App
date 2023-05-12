<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['slug','icon', 'image', 'is_visible', 'status', 'position', 'is_core', 'can_add_products', 'parent_id', 'vendor_id', 'client_code', 'display_mode', 'type_id', 'show_wishlist', 'sub_cat_banners', 'royo_order_category_id', 'order_panel_id'];

    public function warehouses(){
        return $this->belongsToMany('App\Model\Warehouse', 'warehouse_category');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
    public function translation(){
        return $this->hasOne('App\Model\CategoryTranslation','category_id','id');
    }
}

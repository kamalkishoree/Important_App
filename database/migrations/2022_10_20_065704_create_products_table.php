<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 500)->unique('products_sku_unique');
            $table->string('title', 120)->nullable();
            $table->string('url_slug', 100)->nullable();
            $table->longText('description')->nullable();
            $table->longText('body_html')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('country_origin_id')->nullable();
            $table->boolean('is_new')->default(0)->index('products_is_new_index')->comment("0 - no, 1 - yes");
            $table->boolean('is_featured')->default(0)->index('products_is_featured_index')->comment("0 - no, 1 - yes");
            $table->boolean('is_live')->default(0)->index('products_is_live_index')->comment("0 - draft, 1 - published, 2 - blocked");
            $table->boolean('is_physical')->default(0)->index('products_is_physical_index')->comment("0 - no, 1 - yes");
            $table->decimal('weight', 10, 4)->nullable();
            $table->string('weight_unit', 10)->nullable();
            $table->boolean('has_inventory')->default(0)->index('products_has_inventory_index')->comment("0 - no, 1 - yes");
            $table->boolean('has_variant')->default(0)->comment("0 - no, 1 - yes");
            $table->boolean('sell_when_out_of_stock')->default(0)->index('products_sell_when_out_of_stock_index')->comment("0 - no, 1 - yes");
            $table->boolean('requires_shipping')->default(0)->index('products_requires_shipping_index')->comment("0 - no, 1 - yes");
            $table->boolean('Requires_last_mile')->default(0)->index('products_requires_last_mile_index')->comment("0 - no, 1 - yes");
            $table->decimal('averageRating', 4, 2)->nullable()->index('products_averagerating_index');
            $table->boolean('inquiry_only')->default(0);
            $table->dateTime('publish_at')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('tax_category_id')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('pharmacy_check')->default(0)->comment("0-No, 1-Yes");
            $table->string('tags')->nullable();
            $table->string('need_price_from_dispatcher')->nullable();
            $table->string('mode_of_service', 50)->nullable();
            $table->integer('delay_order_hrs')->default(0);
            $table->integer('delay_order_min')->default(0);
            $table->integer('pickup_delay_order_hrs')->default(0);
            $table->integer('pickup_delay_order_min')->default(0);
            $table->integer('dropoff_delay_order_hrs')->default(0);
            $table->integer('dropoff_delay_order_min')->default(0);
            $table->integer('need_shipment')->default(0);
            $table->integer('minimum_order_count')->default(1);
            $table->integer('batch_count')->default(1);
            $table->integer('delay_order_hrs_for_dine_in')->default(0);
            $table->integer('delay_order_min_for_dine_in')->default(0);
            $table->integer('delay_order_hrs_for_takeway')->default(0);
            $table->integer('delay_order_min_for_takeway')->default(0);
            $table->boolean('age_restriction')->default(0)->comment("0-No, 1-Yes");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

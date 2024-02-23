<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInventoryVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index('vendors_name_index');
            $table->mediumText('slug')->nullable();
            $table->text('desc')->nullable();
            $table->string('logo', 150)->nullable();
            $table->string('banner', 150)->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('dial_code')->nullable();
            $table->decimal('latitude', 15, 12)->nullable();
            $table->decimal('longitude', 16, 12)->nullable();
            $table->decimal('order_min_amount', 10, 2)->default(0.00)->index('vendors_order_min_amount_index');
            $table->string('order_pre_time', 40)->nullable()->index('vendors_order_pre_time_index');
            $table->string('auto_reject_time', 40)->nullable()->index('vendors_auto_reject_time_index');
            $table->decimal('commission_percent', 10, 2)->default(1.00)->index('vendors_commission_percent_index');
            $table->decimal('commission_fixed_per_order', 10, 2)->default(0.00)->index('vendors_commission_fixed_per_order_index');
            $table->decimal('commission_monthly', 10, 2)->default(0.00)->index('vendors_commission_monthly_index');
            $table->boolean('dine_in')->default(0)->index('vendors_dine_in_index')->comment("1 for yes, 0 for no");
            $table->boolean('takeaway')->default(0)->index('vendors_takeaway_index')->comment("1 for yes, 0 for no");
            $table->boolean('delivery')->default(0)->index('vendors_delivery_index')->comment("1 for yes, 0 for no");
            $table->boolean('status')->default(1)->comment("1-active, 0-pending, 2-blocked");
            $table->boolean('add_category')->default(1)->index('vendors_add_category_index')->comment("0 for no, 1 for yes");
            $table->boolean('setting')->default(0)->comment("0 for no, 1 for yes");
            $table->boolean('is_show_vendor_details')->default(0);
            $table->timestamps();
            $table->boolean('show_slot')->default(1)->comment("1 for yes, 0 for no");
            $table->unsignedBigInteger('vendor_templete_id')->nullable();
            $table->boolean('auto_accept_order')->default(0)->comment("1 for yes, 0 for no");
            $table->decimal('service_fee_percent', 10, 2)->default(0.00);
            $table->integer('slot_minutes')->nullable();
            $table->boolean('orders_per_slot')->default(0);
            $table->decimal('order_amount_for_delivery_fee', 64, 0)->default(0);
            $table->decimal('delivery_fee_minimum', 64, 2)->default(0.00);
            $table->decimal('delivery_fee_maximum', 64, 2)->default(0.00);
            $table->boolean('closed_store_order_scheduled')->default(0);
            $table->integer('pincode')->nullable();
            $table->string('shiprocket_pickup_name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->boolean('return_request')->default(0)->comment("1 for yes, 0 for no");
            $table->longText('ahoy_location')->nullable();
            $table->boolean('max_safety')->default(0)->comment("0-No, 1-Yes");
            $table->boolean('need_container_charges')->default(0)->comment("0-No, 1-Yes");
            $table->tinyInteger('fixed_fee')->comment("0-No, 1-Yes");
            $table->decimal('fixed_fee_amount', 16, 2);
            $table->boolean('price_bifurcation')->default(0)->comment("0-No, 1-Yes");
            $table->tinyInteger('need_sync_with_order')->default('0')->comment('1 for yes, 0 for no');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_vendors');
    }
}

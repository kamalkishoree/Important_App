<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {

            $table->id();
            $table->string('sku', 500)->index()->unique();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('title')->nullable();
            $table->integer('quantity')->default(0)->index('product_variants_quantity_index');
            $table->decimal('price', 16, 8)->nullable()->index('product_variants_price_index');
            $table->boolean('position')->default(1);
            $table->decimal('compare_at_price', 16, 8)->nullable()->index('product_variants_compare_at_price_index');
            $table->string('barcode', 20)->unique('product_variants_barcode_unique');
            $table->decimal('cost_price', 12, 4)->nullable()->index('product_variants_cost_price_index');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('tax_category_id')->nullable();
            $table->string('inventory_policy')->nullable();
            $table->string('fulfillment_service')->nullable();
            $table->string('inventory_management')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(1)->comment("1 for avtive, 0 for inactive");
            $table->decimal('container_charges', 12, 4)->default(0.0000);
            $table->date('expiry_date')->nullable();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}

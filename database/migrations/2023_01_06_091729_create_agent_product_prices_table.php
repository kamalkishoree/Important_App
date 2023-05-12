<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_product_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id')->unsigned()->nullable();
            $table->string('product_variant_sku')->nullable()->comment('sku is same from order royo_dBname_{ordersku}');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->bigInteger('product_variant_id')->unsigned()->nullable();
            $table->decimal('price',16, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_product_prices');
    }
}

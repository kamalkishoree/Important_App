<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderFormAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_form_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attribute_id')->unsigned()->nullable();
            // $table->string('attribute_value')->nullable();
            $table->bigInteger('attribute_option_id')->unsigned()->nullable();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->string('key_name')->nullable();
            $table->string('key_value')->nullable();
            $table->string('is_active')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1 for dropdown, 2 for color');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('form_attributes')->onDelete('cascade');
            $table->foreign('attribute_option_id')->references('id')->on('form_attribute_options')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_form_attributes');
    }
}

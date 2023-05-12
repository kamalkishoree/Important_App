<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderVendorProducts extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_vendor_products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('name', '255')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('task_id')->nullable();
            $table->integer('vendor_id')->nullable();
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
        Schema::dropIfExists('order_vendor_products');
    }
}

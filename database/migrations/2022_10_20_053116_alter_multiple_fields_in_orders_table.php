<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMultipleFieldsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('cash_to_be_collected', 14, 2)->nullable()->change();
            $table->decimal('base_price', 14, 2)->nullable()->change();
            $table->decimal('base_distance', 14, 2)->nullable()->change();
            $table->decimal('duration_price', 14, 2)->nullable()->change();
            $table->decimal('waiting_price', 14, 2)->nullable()->change();
            $table->decimal('distance_fee', 14, 2)->nullable()->change();
            $table->decimal('cancel_fee', 14, 2)->nullable()->change();
            $table->decimal('actual_time', 10, 2)->nullable()->change();
            $table->decimal('actual_distance', 10, 2)->nullable()->change();
            $table->decimal('order_cost', 16, 2)->nullable()->change();
            $table->decimal('driver_cost', 14, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}

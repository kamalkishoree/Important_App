<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderPanelDetailsAddColumnToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_panel_details', function (Blueprint $table) {
            $table->string('token','255')->nullable();
            $table->integer('is_approved')->default(0);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_panel_details', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}

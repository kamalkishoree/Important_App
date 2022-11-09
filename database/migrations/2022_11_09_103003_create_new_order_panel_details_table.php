<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewOrderPanelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('order_panel_details');
        Schema::create('order_panel_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('code');
            $table->string('key');
            $table->string('status')->default(1)->comment('1 - active, 0 - pending');
            $table->dateTime('last_sync');
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
        Schema::dropIfExists('order_panel_details');
    }
}

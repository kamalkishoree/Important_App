<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclineBidRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decline_bid_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bid_id')->unsigned();
            $table->bigInteger('agent_id')->unsigned();
            $table->tinyInteger('status')->default(0)->comment('1 = accepted, 0 = rejected');
            $table->timestamps();

            $table->foreign('bid_id')->references('id')->on('user_bid_ride_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decline_bid_requests');
    }
}

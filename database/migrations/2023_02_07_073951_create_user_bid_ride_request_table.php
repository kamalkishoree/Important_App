<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBidRideRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bid_ride_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bid_id')->unsigned();
            $table->bigInteger('geo_id')->unsigned();
            $table->string('agent_tag', 100)->nullable();
            $table->string('db_name', 100)->nullable();
            $table->string('client_code', 100)->nullable();
            $table->string('call_back_url', 1000)->nullable();
            $table->string('tasks', 1000)->nullable();
            $table->decimal('requested_price', 15, 4)->nullable();
            $table->dateTime('expired_at', $precision = 0);
            $table->string('customer_name', 200)->nullable();
            $table->string('customer_image', 500)->nullable();
            $table->decimal('minimum_requested_price', 15, 4)->nullable();
            $table->decimal('maximum_requested_price', 15, 4)->nullable();
            $table->string('expire_seconds', 10)->nullable();
            $table->timestamps();
            $table->foreign('geo_id')->references('id')->on('geos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bid_ride_request');
    }
}

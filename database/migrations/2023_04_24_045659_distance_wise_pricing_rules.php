<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DistanceWisePricingRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distance_wise_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_rule_id')->index();
            $table->string('distance_fee')->default(0);
            $table->string('duration_price')->default(0);
            $table->timestamps();
            $table->foreign('price_rule_id')->references('id')->on('price_rules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distance_wise_pricing');
    }
}

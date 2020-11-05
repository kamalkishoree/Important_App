<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->char('is_default')->nullable()->comment('y,n');
            $table->foreignId('geo_id')->nullable();
            $table->foreignId('team_id')->nullable();
            $table->foreignId('team_tag_id')->nullable();
            $table->foreignId('driver_tag_id')->nullable();
            $table->string('base_price')->nullable();
            $table->string('base_duration')->nullable();
            $table->string('base_distance')->nullable();
            $table->string('base_waiting')->nullable();
            $table->string('duration_price')->nullable();
            $table->string('waiting_price')->nullable();
            $table->string('distance_fee')->nullable();
            $table->string('cancel_fee')->nullable();
            $table->string('agent_commission_percentage')->nullable();
            $table->string('agent_commission_fixed')->nullable();
            $table->string('freelancer_commission_percentage')->nullable();
            $table->string('freelancer_commission_fixed')->nullable();
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
        Schema::dropIfExists('price_rules');
    }
}

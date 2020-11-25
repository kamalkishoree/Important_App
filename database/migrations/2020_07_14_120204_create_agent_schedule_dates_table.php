<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentScheduleDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_schedule_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->default(0);
            $table->char('not_working_today')->default('y')->comment('y,n');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('date');
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
        Schema::dropIfExists('agent_schedule_dates');
    }
}
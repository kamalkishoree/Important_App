<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeofenceScheduleWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geofence_schedule_weeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->default(0);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('day_of_week');
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
        Schema::dropIfExists('geofence_schedule_weeks');
    }
}
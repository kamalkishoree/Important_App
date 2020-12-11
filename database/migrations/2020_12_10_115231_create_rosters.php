<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRosters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id')->unsigned()->nullable();
            $table->dateTime('notification_time')->nullable();
            $table->timestamps();
        });

        // Schema::create('rosters', function (Blueprint $table) {
        //     $table->foreign('driver_id')->references('id')->on('agents')->onUpdate('cascade')->onDelete('set null');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rosters');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->default(0);
            $table->string('name');
            $table->string('profile_picture');
            $table->char('type', 150)->comment('Freelancer,In house');
            $table->string('vehicle_type_id');
            $table->string('make_model');
            $table->string('plate_number');
            $table->string('phone_number');
            $table->string('color');
            $table->tinyInteger('is_activated')->default(0);
            $table->tinyInteger('is_available')->default(0)->comment('toggle switch in app');
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
        Schema::dropIfExists('agents');
    }
}
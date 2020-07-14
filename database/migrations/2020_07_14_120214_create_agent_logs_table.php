<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->default(0);
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('battery_level')->nullable();
            $table->string('android_version')->nullable();
            $table->string('app_version')->nullable();
            $table->string('current_task_id')->nullable();
            $table->string('current_speed')->nullable();
            $table->char('on_route for task')->default('y')->comment('y,n');
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
        Schema::dropIfExists('agent_logs');
    }
}
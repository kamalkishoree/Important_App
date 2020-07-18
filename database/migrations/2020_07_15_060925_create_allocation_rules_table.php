<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->default(0);
            $table->char('manual_allocation', 50)->default('y')->comment('y,n');
            $table->char('auto_assign_logic', 150)->comment('one by one,request sent to all, request sent batch wise, one by one forced, round robin, forced to nearest');
            $table->char('request_expiry', 150)->comment('one by one or batch');
            $table->string('number_of_retries')->nullable();
            $table->string('task_priority')->nullable();
            $table->string('start_radius')->nullable();
            $table->string('start_before_task_time')->nullable();
            $table->string('increment_radius')->nullable();
            $table->string('maximum_radius')->nullable();
            $table->string('maximum_batch_size')->nullable();
            $table->string('maximum_batch_count')->nullable();
            $table->string('maximum_task_per_person')->nullable();
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
        Schema::dropIfExists('allocation_rules');
    }
}
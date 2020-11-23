<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table('tasks', function($table) {
            $table->dropColumn(['name','from_address','to_address','status','priority','expected_delivery_date','created_at','updated_at']);
        });
            Schema::table('tasks', function($table) {
                $table->foreignId('order_id')->nullable();
               $table->foreignId('dependent_task_id')->nullable();
               $table->foreignId('task_type_id')->nullable();
               $table->foreignId('location_id')->nullable();
               $table->dateTime('appointment_duration')->nullable();
               $table->foreignId('pricing_rule_id')->nullable();
               $table->string('distance')->nullable();
               $table->dateTime('assigned_time')->nullable();
               $table->dateTime('accepted_time')->nullable();
               $table->dateTime('declined_time')->nullable();
               $table->dateTime('started_time')->nullable();
               $table->dateTime('reached_time')->nullable();
               $table->dateTime('failed_time')->nullable();
               $table->dateTime('cancelled_time')->nullable();
               $table->string('cancelled_by_admin_id')->nullable();
               $table->dateTime('Completed_time')->nullable();
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
        
    }
}

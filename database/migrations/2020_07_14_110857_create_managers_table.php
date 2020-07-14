<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->tinyInteger('can_create_task')->default(0);
            $table->tinyInteger('can_edit_task_created')->default(0);
            $table->tinyInteger('can_edit_all')->default(0);
            $table->tinyInteger('can_manage_unassigned_tasks')->default(0);
            $table->tinyInteger('can_edit_auto_allocation')->default(0);
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
        Schema::dropIfExists('managers');
    }
}
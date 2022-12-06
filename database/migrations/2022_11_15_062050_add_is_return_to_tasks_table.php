<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReturnToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->double('current_lat')->nullable();
            $table->double('current_long')->nullable();
            $table->tinyInteger('is_return')->default(0)->comment('1 = Return, 0 = No Return');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('current_lat');
            $table->dropColumn('current_long');
            $table->dropColumn('is_return');

        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelfAssignToAllocationRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('allocation_rules', function (Blueprint $table) {
            $table->string('self_assign')->default('n')->comment('y,n');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allocation_rules', function (Blueprint $table) {
            $table->dropColumn('self_assign');
        });
    }
}

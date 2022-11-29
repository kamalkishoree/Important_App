<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCabPoolingToRosterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roster_details', function (Blueprint $table) {
            $table->tinyInteger('is_cab_pooling')->default(0)->nullable()->comment('0-No, 1-Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roster_details', function (Blueprint $table) {
            //
        });
    }
}

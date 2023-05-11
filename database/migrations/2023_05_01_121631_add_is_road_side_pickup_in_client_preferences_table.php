<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRoadSidePickupInClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('is_road_side_pickup')->default(0)->comment('0-No, 1-Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn('is_road_side_pickup');
        });
    }
}

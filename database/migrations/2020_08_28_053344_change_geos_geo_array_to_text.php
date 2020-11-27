<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGeosGeoArrayToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('geos', function (Blueprint $table) {
            $this->changeColumnType('geos','geo_array','TEXT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('geos', function (Blueprint $table) {
            //
        });
    }

    public function changeColumnType($table, $column, $newColumnType) {                
        DB::statement("ALTER TABLE $table CHANGE $column $column $newColumnType");
    }
}

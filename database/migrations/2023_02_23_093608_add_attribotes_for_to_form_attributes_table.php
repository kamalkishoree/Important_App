<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttribotesForToFormAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_attributes', function (Blueprint $table) {
            $table->tinyInteger('attribute_for')->default('1')->comment('1 - default, 2 - driver_rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_attributes', function (Blueprint $table) {
            $table->dropColumn('attribute_for');
        });
    }
}

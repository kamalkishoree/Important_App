<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToCurrencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->integer('priority');
            $table->string('iso_code');
            $table->string('symbol');
            $table->string('subunit');
            $table->integer('subunit_to_unit');
            $table->boolean('symbol_first');
            $table->string('html_entity');
            $table->string('decimal_mark');
            $table->string('thousands_separator');
            $table->integer('iso_numeric');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('priority');
            $table->dropColumn('iso_code');
            $table->dropColumn('symbol');
            $table->dropColumn('subunit');
            $table->dropColumn('subunit_to_unit');
            $table->dropColumn('symbol_first');
            $table->dropColumn('html_entity');
            $table->dropColumn('decimal_mark');
            $table->dropColumn('thousands_separator');
            $table->dropColumn('iso_numeric');
        });
    }
}

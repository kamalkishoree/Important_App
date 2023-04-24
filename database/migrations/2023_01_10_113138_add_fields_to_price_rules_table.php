<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPriceRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            $table->decimal('base_price_minimum', 6, 2)->nullable();
			$table->string('base_duration_minimum', 15)->nullable();
			$table->decimal('base_distance_minimum', 4, 3)->nullable();
			$table->string('base_waiting_minimum', 15)->nullable();
			$table->decimal('duration_price_minimum', 4, 2)->nullable();
			$table->decimal('waiting_price_minimum', 4, 2)->nullable();
			$table->decimal('distance_fee_minimum', 4, 2)->nullable();
            $table->decimal('base_price_maximum', 6, 2)->nullable();
			$table->string('base_duration_maximum', 15)->nullable();
			$table->decimal('base_distance_maximum', 4, 3)->nullable();
			$table->string('base_waiting_maximum', 15)->nullable();
			$table->decimal('duration_price_maximum', 4, 2)->nullable();
			$table->decimal('waiting_price_maximum', 4, 2)->nullable();
			$table->decimal('distance_fee_maximum', 4, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_rules', function (Blueprint $table) {
            //
        });
    }
}

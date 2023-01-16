<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPreferenceAdditionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_preference_additional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('client_code', 10)->nullable();
            $table->string('key_name')->nullable();
            $table->string('key_value')->nullable();
            $table->string('description')->nullable();
            $table->string('is_active')->nullable();
            $table->string('is_private')->nullable();
            $table->string('is_boolean')->nullable();
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
        Schema::dropIfExists('client_preference_additional');
    }
}

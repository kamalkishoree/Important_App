<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormAttributeOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_attribute_option_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->bigInteger('attribute_option_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('attribute_option_id')->references('id')->on('form_attribute_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_attribute_option_translations');
    }
}

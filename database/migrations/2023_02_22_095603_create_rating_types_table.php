<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('is_enabled')->default(0)->comment('1 = accepted, 0 = rejected');
            $table->tinyInteger('is_take_reviews')->default(0)->comment('1 = accepted, 0 = rejected');
            $table->softDeletes();
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
        Schema::dropIfExists('rating_types');
    }
}

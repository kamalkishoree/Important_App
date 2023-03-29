<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsHomeAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents_home_address', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id')->unsigned()->nullable();
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->decimal('latitude', 10, 8)->default(0);
			$table->decimal('longitude', 12, 8)->default(0);
			$table->string('short_name', 50)->nullable();
			$table->string('address', 100)->nullable();
			$table->integer('post_code')->nullable();
            $table->tinyInteger('is_default')->default(1)->comment('1 for active, 0 for deactive');
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
        Schema::dropIfExists('agents_home_address');
    }
}

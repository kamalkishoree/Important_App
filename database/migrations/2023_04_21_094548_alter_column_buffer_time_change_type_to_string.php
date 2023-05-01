<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnBufferTimeChangeTypeToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('buffer_time', 40)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('buffer_time')->nullable()->change();
        });
    }
    
}

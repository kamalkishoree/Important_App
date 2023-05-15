<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWarehousesAddColumnPhoneNumberAndEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('email', 255)->nullable();
            $table->string('phone_no', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('phone_no');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterClientPreferencesAddColumnIsDispatcher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('is_dispatcher_allocation')->default(0)->after('threshold_data');
            $table->tinyInteger('use_large_hub')->default(0)->after('is_dispatcher_allocation');
        });
    }
    
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn('is_dispatcher_allocation');
            $table->dropColumn('use_large_hub');
        });
    }
}

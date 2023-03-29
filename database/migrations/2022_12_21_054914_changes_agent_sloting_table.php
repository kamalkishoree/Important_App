<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesAgentSlotingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_slots', function (Blueprint $table) {
            $table->dropForeign('agent_slots_agent_id_foreign');
            $table->bigInteger('general_slots')->default(0)->comment('1 yes, 0 no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_slots', function (Blueprint $table) {
            //
        });
    }
}

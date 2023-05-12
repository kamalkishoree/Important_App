<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentCashCollectPopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_cash_collect_pop', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id')->unsigned();
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('transaction_id')->nullable();
            $table->dateTime('date');
            $table->string('file')->nullable();
            $table->tinyInteger('payment_type')->default(0)->comment('1 Automatcally, 0 Manually');
            $table->enum('threshold_type', [1, 2,3])->nullable();
            $table->longText('reason')->nullable()->comment('If admin/vendor rejected pop request');;
            $table->tinyInteger('status')->default(0)->comment('1 Approval, 0 Pending,2=Rejected');
            $table->timestamps();
        });

        Schema::table('agent_cash_collect_pop', function (Blueprint $table) {
            $table->foreign('agent_id')
                  ->references('id')->on('agents')
                  ->onDelete('cascade');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_cash_collect_pop');
    }
}

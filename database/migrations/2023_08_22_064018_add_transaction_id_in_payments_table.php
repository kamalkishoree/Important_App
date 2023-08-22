<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionIdInPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('amount')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('balance_transaction')->nullable();
            $table->string('type')->nullable();
            $table->string('date')->nullable();
            $table->string('payment_option_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount',
            'transaction_id',
            'balance_transaction',
            'type',
            'date',
            'payment_option_id']);
        });
    }
}

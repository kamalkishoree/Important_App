<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transaction', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount',12,2)->default(0);
            $table->string('transaction_id', 255)->nullable()->change();
            $table->string('gateway_reference', 255)->nullable();
            $table->string('order_reference', 255)->nullable();
            $table->string('otp', 255)->nullable();
            $table->tinyInteger('otp_verified')->default(0)->nullable()->comments('0 = Not Verified, 1 = Verified');
            $table->string('balance_transaction', '255')->nullable();
            $table->string('type', '255')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->unsignedBigInteger('user_subscription_invoice_id')->nullable();
            $table->unsignedBigInteger('vendor_subscription_invoice_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->unsignedBigInteger('payment_option_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('viva_order_id')->nullable();
            $table->integer('payment_from')->default(0)->comment('0 wallet, 1 off-the-platform');
            $table->bigInteger('reference_table_id')->unsigned()->nullable();
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
        Schema::dropIfExists('payment_transaction');
    }
}

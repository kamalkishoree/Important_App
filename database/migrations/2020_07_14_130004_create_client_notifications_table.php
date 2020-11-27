<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->default(0);
            $table->string('webhook_url')->nullable();
            $table->tinyInteger('request_recieved_sms')->default(0);
            $table->tinyInteger('request_received_email')->default(0);
            $table->tinyInteger('request_recieved_webhook')->default(0);
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
        Schema::dropIfExists('client_notifications');
    }
}
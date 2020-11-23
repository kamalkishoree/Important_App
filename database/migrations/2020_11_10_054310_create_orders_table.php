<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable();
            $table->dateTime('scheduled_date_time')->nullable();
            $table->text('key_value_set')->nullable();
            $table->text('conditions_tag_team')->nullable();
            $table->text('conditions_tag_driver')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('Recipient_email')->nullable();
            $table->string('task_description')->nullable();
            $table->string('images_array')->nullable();
            $table->string('auto_alloction')->nullable();
            $table->foreignId('driver_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

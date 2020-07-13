<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->default(0);
            $table->string('theme')->default('light')->comment('light,dark');
            $table->string('distance_unit')->nullable();
            $table->foreignId('currency_id')->default(0);
            $table->foreignId('language_id')->default(0);
            $table->string('agent_name')->nullable()->comment('Driver, Service Provider etc.');
            $table->string('map_type')->default('google')->comment('google,mapbox');
            $table->string('map_key_1')->nullable();
            $table->string('map_key_2')->nullable();
            $table->string('sms_provider')->nullable();
            $table->string('sms_provider_key_1')->nullable();
            $table->string('sms_provider_key_2')->nullable();
            $table->char('allow_feedback_tracking_url')->default('n')->comment('y,n');
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
        Schema::dropIfExists('client_preferences');
    }
}

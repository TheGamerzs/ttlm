<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('discord_snowflake')->unique();
            $table->string('name');
            $table->string('tt_id')->nullable();
            $table->string('api_public_token')->nullable();
            $table->string('truckCompacity')->nullable();
            $table->string('pocketCompacity')->nullable();
            $table->string('trainYardCompacity')->nullable();
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
        Schema::dropIfExists('users');
    }
};

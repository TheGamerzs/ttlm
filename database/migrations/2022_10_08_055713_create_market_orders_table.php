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
        Schema::create('market_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);
            $table->enum('type', ['buy', 'sell', 'move']);
            $table->unsignedInteger('count');
            $table->unsignedInteger('price_each');
            $table->string('item_name');
            $table->string('storage');
            $table->string('storage_additional')->nullable();
            $table->text('details')->nullable();
            $table->timestamp('expires')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('market_orders');
    }
};

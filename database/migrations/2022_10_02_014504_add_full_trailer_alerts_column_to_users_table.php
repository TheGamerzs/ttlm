<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->json('full_trailer_alerts')->nullable();
        });

        foreach (User::all() as $user) {
            $user->full_trailer_alerts = collect([
                'scrap_ore',
                'scrap_emerald',
                'petrochem_petrol',
                'petrochem_propane',
                'scrap_plastic',
                'scrap_copper',
                'refined_copper',
                'refined_zinc',
            ]);
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

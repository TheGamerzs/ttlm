<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Use factory for default full trailer alerts.
        User::factory()->create([
            'discord_snowflake' => 324060102770556933,
            'name' => 'xxdalexx',
            'tt_id' => 645753,
            'truckCapacity' => 9775,
            'pocketCapacity' => 325,
            'trainYardCapacity' => 45161,
        ]);
    }
}

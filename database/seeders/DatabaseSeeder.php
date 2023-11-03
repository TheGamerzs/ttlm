<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MarketOrder;
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
        $user = User::factory()->create([
            'discord_snowflake' => 730142125181894657,
            'name' => 'thegamerzs',
            'tt_id' => 56112,
            'truckCapacity' => 9775,
            'truckCapacityTwo' => 6000,
            'pocketCapacity' => 325,
            'trainYardCapacity' => 65228,
        ]);

        MarketOrder::factory()->buyOrder()->count(2)->for($user)->create();
        MarketOrder::factory()->sellOrder()->count(2)->for($user)->create();
        MarketOrder::factory()->moveOrder()->count(1)->for($user)->create();
    }
}

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
            'discord_snowflake' => 324060102770556933,
            'name' => 'xxdalexx',
            'tt_id' => 645753,
            'truckCapacity' => 9775,
            'truckCapacityTwo' => 6000,
            'pocketCapacity' => 325,
            'trainYardCapacity' => 65228,
        ]);

        MarketOrder::factory()->buyOrder()->count(2)->for($user)->create();
        MarketOrder::factory()->sellOrder()->count(2)->for($user)->create();
        MarketOrder::factory()->moveOrder()->count(1)->for($user)->create();

        $otherUser = User::factory()->create();

        MarketOrder::factory()->buyOrder()->count(10)->for($otherUser)->create();
        MarketOrder::factory()->sellOrder()->count(10)->for($otherUser)->create();
        MarketOrder::factory()->moveOrder()->count(5)->for($otherUser)->create();
        MarketOrder::factory()->buyOrder()->for($otherUser)->create(['item_name' => 'biz_token']);
    }
}

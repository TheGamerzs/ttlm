<?php

namespace Database\Factories;

use App\Models\User;
use App\TT\Items\ItemData;
use App\TT\Items\Weights;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarketOrder>
 */
class MarketOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'    => User::factory(),
            'item_name'  => $this->fakeItem(),
            'expires'    => Carbon::now()->addWeek(),
            'count'      => fake()->numberBetween(1, 9) * 100,
            'price_each' => fake()->numberBetween(15, 800) * 1000,
            'storage'    => $this->fakeStorage(),
            'details' => rand(0,1) == 1 ? fake()->paragraph(2) : null
        ];
    }

    public function buyOrder()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'buy',
                'storage_additional' => rand(0,1) == 1 ? $this->fakeStorage() : null
            ];
        });
    }

    public function sellOrder()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'sell',
                'storage_additional' => null
            ];
        });
    }

    public function moveOrder()
    {
        return $this->state(function (array $attributes) {
            return [
                'price_each' => fake()->numberBetween(4,9) * 100,
                'type' => 'move',
                'storage_additional' => $this->fakeStorage()
            ];
        });
    }

    protected function fakeItem(): string
    {
        return ItemData::getAllInternalTruckingNames()->random();
    }

    protected function fakeStorage(): string
    {
        return App::get('storageData')->random()->id;
    }
}

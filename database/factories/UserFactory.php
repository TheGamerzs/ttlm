<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'discord_snowflake' => $this->fakeDiscordSnowflake(),
            'name' => fake()->userName(),
            'tt_id' => fake()->randomNumber(6),
//            'api_public_key' => Str::random(37),
            'trainYardCapacity' => fake()->randomNumber(6),
            'pocketCapacity' => fake()->randomNumber(3),
            'truckCapacity' => fake()->randomNumber(5),
            'full_trailer_alerts' => $this->getFullTrailerAlerts(),
            'calls_made' => 1,
            'hidden_exportable_items' => collect(),
            'custom_combined_storage' => collect()
        ];
    }

    protected function getFullTrailerAlerts(): Collection
    {
        return collect([
            'scrap_ore',
            'scrap_emerald',
            'petrochem_petrol',
            'petrochem_propane',
            'scrap_plastic',
            'scrap_copper',
            'refined_copper',
            'refined_zinc',
        ]);
    }

    protected function fakeDiscordSnowflake()
    {
        return (string) fake()->randomNumber(9) . fake()->randomNumber(9);
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            'api_private_key' => Str::random(37),
            'trainYardCompacity' => fake()->randomNumber(6),
            'pocketCompacity' => fake()->randomNumber(3),
            'truckCompacity' => fake()->randomNumber(5)
        ];
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

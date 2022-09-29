<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Contracts\Factory as Socialite;

class DiscordController
{
    public static function routes()
    {

    }

    protected Socialite $socialite;

    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }

    public function redirectToDiscord()
    {
        return $this->socialite->driver('discord')
//            ->scopes(['identify', 'guilds', 'email'])
            ->redirect();
    }

    public function handleCallback()
    {
        $discordUserResponse = $this->socialite->driver('discord')->user();

        $discordSnowflake = $discordUserResponse->getId();
        $discordUserName = $discordUserResponse->getName();

        $user = User::firstOrCreate(
            ['discord_snowflake' => $discordSnowflake],
            ['name' => $discordUserName]
        );

        if ($user->wasRecentlyCreated) {
            $user->setTTIdFromApi();
        }

        Auth::login($user);

        if (! $user->canMakeApiCall()) {
            return redirect()->route('userSettings');
        }

        return redirect()->route('home');
    }
}

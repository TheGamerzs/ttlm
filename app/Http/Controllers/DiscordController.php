<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use PhpParser\Node\Stmt\TryCatch;

class DiscordController
{
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
        try {
            $discordUserResponse = $this->socialite->driver('discord')->user();
        } catch (InvalidStateException|ClientException $exception) {
            return redirect()->route('login');
        }

        $discordSnowflake = $discordUserResponse->getId();
        $discordUserName = $discordUserResponse->getName();

        $user = User::firstOrCreate(
            ['discord_snowflake' => $discordSnowflake],
            ['name' => $discordUserName]
        );

        if ($user->wasRecentlyCreated) {
            Cache::put($user->id . 'apiIdAttempts', 3);
            $user->setTTIdFromApi();
        } else {
            $user->touch();
        }

        Auth::login($user);

        if (! $user->canMakeApiCall()) {
            return redirect()->route('userSettings');
        }

        return redirect()->route('home');
    }
}

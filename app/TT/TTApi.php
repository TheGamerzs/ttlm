<?php

namespace App\TT;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TTApi
{
    public static function storages()
    {
        throw_if(!Auth::check(), 'No Logged In User');
        $user = Auth::user();
        throw_if(! $user->canMakeApiCall(), 'User missing required data for API call.');

        return Cache::rememberForever(Auth::id() . 'tt_api_storage', function () use ($user) {
            $response = Http::withHeaders(['X-Tycoon-Key' => $user->api_private_key])
                ->get('v1.api.tycoon.community/main/storages/' . $user->tt_id);

            if ($response->clientError()) {
                abort(401, 'API Call Failed');
            }

            Cache::put($user->id . 'api_charges', $response->headers()['X-Tycoon-Charges'][0]);

            return $response->body();
        });
    }

    public static function ttIdFromDiscordSnowflake(string $snowflake): \stdClass
    {
        $response = Http::withHeaders(['X-Tycoon-Key' => config('app.tt_api_private_key')])
            ->get('v1.api.tycoon.community/main/snowflake2user/' . $snowflake);

        return json_decode($response->body());
    }
}

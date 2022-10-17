<?php

namespace App\TT;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TTApi
{
    public User $user;

    public function __construct()
    {
        throw_if(!Auth::check(), 'Attempted to create an API object without a logged in user.');

        $this->user = Auth::user();
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function buildHeaders(): array
    {
        $header = [
            'X-Tycoon-Key' => config('app.tt_api_private_key')
        ];

        if (Auth::user()->usesPublicKey()) {
            $header['X-Tycoon-Public-Key'] = Auth::user()->api_public_key;
        }

        return $header;
    }

    public function getStorages()
    {
        return Cache::rememberForever($this->userStorageCacheKey(), function () {
            $response = Http::withHeaders($this->buildHeaders())
                ->get('v1.api.tycoon.community/main/storages/' . $this->user->tt_id);

            if ($response->clientError()) {
                abort(401, 'API Call Failed');
            }

            $this->user->increment('calls_made');

            // Create a 10-second cool down to check against.
            Cache::put($this->user->id . 'lockedApi', now(), 10);

            return $response->body();
        });
    }

    public function getUserData(): \stdClass
    {
        $responseBody =  Cache::rememberForever($this->userDataCacheKey(), function () {
            $response = Http::withHeaders($this->buildHeaders())
                ->get('v1.api.tycoon.community/main/data/' . $this->user->tt_id);

            if ($response->clientError()) {
                abort(401, 'API Call Failed');
            }

            $this->user->increment('calls_made');

            // Create a 10-second cool down to check against.
            Cache::put($this->user->id . 'lockedApi', now(), 10);

            return $response->body();
        });

        return json_decode($responseBody);
    }

    public function getUserInventory(bool $asCollection = true): Collection|\stdClass
    {
        if ($asCollection) {
            return collect($this->getUserData()->data->inventory);
        }
        return $this->getUserData()->data->inventory;
    }

    public function userStorageCacheKey(): string
    {
        return $this->user->id . 'tt_api_storage';
    }

    public function userDataCacheKey(): string
    {
        return $this->user->id . 'tt_api_user_data';
    }

    public static function ttIdFromDiscordSnowflake(string $snowflake): \stdClass
    {
        $response = Http::withHeaders(['X-Tycoon-Key' => config('app.tt_api_private_key')])
            ->get('v1.api.tycoon.community/main/snowflake2user/' . $snowflake);
        return json_decode($response->body());
    }

    public static function ttItemDataFromInternalName(string $internalName): \stdClass
    {
        $response = Http::withHeaders(['X-Tycoon-Key' => config('app.tt_api_private_key')])
            ->get('v1.api.tycoon.community/main/iteminfo/' . $internalName);
        return json_decode($response->body());
    }
}

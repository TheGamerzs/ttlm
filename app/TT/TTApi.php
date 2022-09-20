<?php

namespace App\TT;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TTApi
{
    public static function storages()
    {
        return Cache::rememberForever('tt_api_storage', function () {
            $response = Http::withHeaders(['X-Tycoon-Key' => config('app.tt_api_private_key')])
                ->get('v1.api.tycoon.community/main/storages/645753');

            Cache::put('api_charges', $response->headers()['X-Tycoon-Charges'][0]);

            return $response->body();
        });
    }
}

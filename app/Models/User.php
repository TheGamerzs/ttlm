<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\TT\TTApi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function setTTIdFromApi(): bool
    {
        // Theoretically, this should never evaluate false
        if (! $this->discord_snowflake) {
            return false;
        }

        $apiResponse = TTApi::ttIdFromDiscordSnowflake($this->discord_snowflake);

        if (property_exists($apiResponse, 'user_id')) {
            $this->update(['tt_id' => $apiResponse->user_id]);
            return true;
        } else {
            Session::flash('cantGetTTApiAlert', true);
            return false;
        }
    }

    public function canMakeApiCall(): bool
    {
        return $this->api_private_key && $this->tt_id;
    }

    public function canCalculate(): bool
    {
        return $this->truckCapacity && $this->pocketCapacity;
    }

    public function hasTrainYard(): bool
    {
        return (bool) $this->trainYardCapacity;
    }
}

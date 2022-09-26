<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function canMakeApiCall(): bool
    {
        return $this->api_private_key && $this->tt_id;
    }

    public function canCalculate(): bool
    {
        return $this->truckCompacity && $this->pocketCompacity;
    }

    public function hasTrainYard(): bool
    {
        return (bool) $this->trainYardCompacity;
    }
}

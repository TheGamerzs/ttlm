<?php

namespace App\Models;

use App\TT\Items\Item;
use App\TT\StorageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketOrder extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeBuyOrders(Builder $query): Builder
    {
        return $query->where('type', 'buy');
    }

    public function scopeSellOrders(Builder $query): Builder
    {
        return $query->where('type', 'sell');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getItemAttribute(): Item
    {
        return new Item($this->item_name);
    }

    public function getTotalCostAttribute(): int
    {
        return $this->price_each * $this->count;
    }

    public function getStorageNameAttribute(): string
    {
        return StorageFactory::getPrettyName($this->storage);
    }

    public function findInverseOrders(): EloquentCollection
    {
        if ($this->type == 'move') return new EloquentCollection();

        $lookupType = $this->type == 'sell'
            ? 'buy'
            : 'sell';

        return self::whereType($lookupType)->whereItemName($this->item_name)->get();
    }
}

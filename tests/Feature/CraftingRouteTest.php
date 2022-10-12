<?php

use App\Models\User;
use App\TT\StorageFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('crafting route', function () {

    StorageFactory::$storages['testing'] = new \App\TT\Storage([
        new \App\TT\Items\InventoryItem('refined_planks', 2)
    ]);
    StorageFactory::$storages['combined'] = StorageFactory::$storages['testing'];

    Http::preventStrayRequests();
    actingAs(User::factory()->create());

    get(route('craftingPage'))->assertOk();

});

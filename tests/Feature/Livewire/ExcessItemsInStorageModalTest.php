<?php

use App\Http\Livewire\ExcessItemsInStorageModal;
use App\Models\User;
use App\TT\Items\ExcessItem;
use Illuminate\Support\Collection;
use function Pest\Laravel\actingAs;

test('getItems method', function () {

    fakeStoragesAndPersonalInventoryCallsWithJson();
    actingAs(User::factory()->create());

    /** @var Collection $items */
    $items = Livewire::test(ExcessItemsInStorageModal::class)
        ->set('count', 500)
        ->set('recipe', 'house')
        ->instance()
        ->getItems();

    expect($items->count())->toBe(5);

    $shouldExist = collect([
        'scrap_ore',
        'refined_zinc',
        'scrap_aluminum',
        'refined_aluminum',
        'scrap_lead',
    ]);
    $items->each(fn (ExcessItem $item) => expect($shouldExist->contains($item->name)) );
});

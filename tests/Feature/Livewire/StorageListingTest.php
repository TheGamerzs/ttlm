<?php

use App\Http\Livewire\StorageListing;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('it works', function () {

    actingAs($user = User::factory()->create());
    $trunkCapacity = $user->makeTruckingInventories()->totalAvailableCapacity();
    fakeStoragesAndPersonalInventoryCallsWithJson();
    Livewire::test(StorageListing::class, ['truckCapacity' => $trunkCapacity])
        ->assertOk();

});

test('updatedHiddenExportableInputs method', function () {

    /** @var User $user */
    $user = mock(User::class)
        ->makePartial()
        ->expects()->save()
        ->getMock();
    Auth::login($user);
    $component = new StorageListing;
    $component->hiddenExportableInputs = ['one' => 0, 'two' => 0];
    $component->updatedHiddenExportableInputs();

    expect($user->hidden_exportable_items)
        ->contains('one')->toBeTrue()
        ->contains('two')->toBeTrue();

});

test('updatedCustomStorageInput method', function () {

    /** @var User $user */
    $user = mock(User::class)
        ->makePartial()
        ->expects()->save()
        ->getMock();
    Auth::login($user);
    $component = new StorageListing;
    $component->customStorageInput = ['one' => 1, 'two' => 1];
    $component->updatedCustomStorageInput();

    expect($user->custom_combined_storage)
        ->contains('one')->toBeTrue()
        ->contains('two')->toBeTrue();

});

test('addItemToFullTrailerAlerts method', function () {

    /** @var User $user */
    $user = mock(User::class)
        ->makePartial()
        ->allows([
            'save' => true
        ])
        ->expects()->addItemToFullTrailerAlerts('crafted_rebar')
        ->getMock();
    $user->full_trailer_alerts = collect();
    Auth::login($user);

    $component = new StorageListing;
    $component->itemToAddToFullTrailerAlerts = 'crafted_rebar';
    $component->addItemToFullTrailerAlerts();

});

test('removeItemFromFullTrailerAlerts method', function () {

    /** @var User $user */
    $user = mock(User::class)
        ->makePartial()
        ->allows([
            'save' => true
        ])
        ->expects()->removeItemFromFullTrailerAlerts('crafted_rebar')
        ->getMock();
    $user->full_trailer_alerts = collect();
    Auth::login($user);

    $component = new StorageListing;
    $component->removeItemFromFullTrailerAlerts('crafted_rebar');

});

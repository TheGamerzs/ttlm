<?php

function assertAlertSet(\Illuminate\Support\Collection $component): void
{
    $alert = $component['alert'];
    expect($alert)->toBeArray()
        ->and(array_key_exists('title', $alert))->toBeTrue()
        ->and(array_key_exists('message', $alert))->toBeTrue()
        ->and(array_key_exists('type', $alert))->toBeTrue();
}

test('defaults', function (string $trigger) {

    Session::put($trigger, true);
    $component = Livewire::test(\App\Http\Livewire\AlertListener::class)->instance()->getHydratedData();

    assertAlertSet($component);

})->with([
    'failedApiAlert',
    'noCapacitiesSetAlert',
    'cantGetTTApiAlert',
]);

test('nothing happens without a default', function () {

    $component = Livewire::test(\App\Http\Livewire\AlertListener::class)->instance()->getHydratedData();

    expect($component['alert'])->toBe([]);

});

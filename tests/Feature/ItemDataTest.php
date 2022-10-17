<?php

use App\TT\Items\ItemData;
use Illuminate\Support\Collection;

test('data is loaded from json file into cache from app service provider', function () {

    expect(Cache::has('itemData'))->toBeFalse()
        ->and(App::get('itemData'))->toBeInstanceOf(Collection::class)
        ->and(App::get('itemData')->count())->toBeGreaterThan(100)
        ->and(Cache::has('itemData'))->toBeTrue();

});

test('itemData in service container is a singleton', function () {

    expect(App::get('itemData') === App::get('itemData'))->toBeTrue();

});

it('returns data for a single item', function () {

    $return = ItemData::getFromDataId('refined_planks');

    expect($return)->not()->toBeNull()
        ->and($return->id)->toBe('refined_planks')
        ->and($return->name)->toBe('Truck Cargo: Planks')
        ->and($return->weight)->toBe('15');

});

test('getFromInternalName alias', function () {

    $fromId = ItemData::getFromDataId('refined_planks');
    $fromName = ItemData::getFromInternalName('refined_planks');

    expect($fromId)->toBe($fromName);

});

it('returns a weight', function () {

    $weight = ItemData::getWeight('refined_planks');

    expect($weight)->toBe(15)->toBeInt();

});

it('returns a weight of 0 for a missing item', function () {

    expect(
        ItemData::getWeight('made_up')
    )->toBe(0);

});

it('returns a pretty name where one exists', function () {

    expect(
        ItemData::getName('refined_planks')
    )->toBe('Truck Cargo: Planks');

});

it('returns a pretty name without the prefix', function () {

    expect(
        ItemData::getName('refined_planks', true)
    )->toBe('Planks');

});

it('returns back the internal name when it does not exist', function () {

    expect(
        ItemData::getName('made_up')
    )->toBe('made_up');

});

it('handles dynamic name cases', function (string $nameFromStorage, string $expectedInternalName, string $expectedDisplayName) {

    $object = ItemData::getFromInternalName($nameFromStorage);

    expect(Cache::has('missingItemNames'))->toBeFalse()
        ->and($object->id)->toBe($expectedInternalName)
        ->and($object->name)->toBe($expectedDisplayName);

})->with([
    [
        'money_card|1517',
        'money_card',
        'Gift Card: $1,517'
    ]
]);

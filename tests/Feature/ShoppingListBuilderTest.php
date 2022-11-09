<?php

use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use Illuminate\Support\Collection;

test('simple recipe', function () {

    $list = ShoppingListBuilder::build(
        RecipeFactory::get('refined_bronze'),
        new Storage(),
        2,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];

    expect($scrap)->toHaveCount(3)
        ->and($scrap->keys())->toContain('scrap_aluminum')
        ->and($scrap->keys())->toContain('scrap_copper')
        ->and($scrap->keys())->toContain('scrap_tin')
        ->and($scrap['scrap_aluminum']->count)->toBe(1)
        ->and($scrap['scrap_copper']->count)->toBe(2)
        ->and($scrap['scrap_tin']->count)->toBe(1);

});

test('a simple recipe with a storage that contains components', function () {

    $storage = new Storage([
        new \App\TT\Items\InventoryItem('scrap_aluminum', 1),
        new \App\TT\Items\InventoryItem('scrap_copper', 1),
        new \App\TT\Items\InventoryItem('scrap_tin', 1),
    ]);
    $list = ShoppingListBuilder::build(
        RecipeFactory::get('refined_bronze'),
        $storage,
        4,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];

    expect($scrap)->toHaveCount(3)
        ->and($scrap->keys())->toContain('scrap_aluminum')
        ->and($scrap->keys())->toContain('scrap_copper')
        ->and($scrap->keys())->toContain('scrap_tin')
        ->and($scrap['scrap_aluminum']->count)->toBe(1)
        ->and($scrap['scrap_copper']->count)->toBe(3)
        ->and($scrap['scrap_tin']->count)->toBe(1);

});

test('a simple recipe with a storage that contains initial requested item', function () {

    // Given a storage with 4 bronze and requesting counts for 8, expect counts for 4 to be returned.
    $storage = new Storage([
        new \App\TT\Items\InventoryItem('refined_bronze', 4),
    ]);
    $list = ShoppingListBuilder::build(
        RecipeFactory::get('refined_bronze'),
        $storage,
        8,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];

    expect($scrap)->toHaveCount(3)
        ->and($scrap->keys())->toContain('scrap_aluminum')
        ->and($scrap->keys())->toContain('scrap_copper')
        ->and($scrap->keys())->toContain('scrap_tin')
        ->and($scrap['scrap_aluminum']->count)->toBe(2)
        ->and($scrap['scrap_copper']->count)->toBe(4)
        ->and($scrap['scrap_tin']->count)->toBe(2);

});

test('a less simple recipe', function () {

    /*
     * crafted_concrete
     *  - crafted_cement => 5
     *       - tcargodust   => 2
     *       - refined_sand => 5
     *  - liquid_water => 1
     *       - liquid_water_raw => 1
     *       - scrap_acid       => 1
     *
     *  expected scrap yields for 1 recipe
     *    tcargodust       => 10
     *    refined_sand     => 25
     *    liquid_water_raw => 1
     *    scrap_acid       => 1
     *
     *  expected refined yields for 1 recipe
     *    crafted_cement   => 5
     *    liquid_water     => 1
     * */
    $list = ShoppingListBuilder::build(
        RecipeFactory::get('crafted_concrete'),
        new Storage(),
        1,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];
    $refined = $list['refined'];
    $crafted = $list['crafted'];

    expect($scrap)->toHaveCount(4)
        ->and($scrap->keys())->toContain('tcargodust')
        ->and($scrap->keys())->toContain('refined_sand')
        ->and($scrap->keys())->toContain('liquid_water_raw')
        ->and($scrap->keys())->toContain('scrap_acid')
        ->and($scrap['tcargodust']->count)->toBe(10)
        ->and($scrap['refined_sand']->count)->toBe(25)
        ->and($scrap['liquid_water_raw']->count)->toBe(1)
        ->and($scrap['scrap_acid']->count)->toBe(1)
        ->and($refined)->toHaveCount(1)
        ->and($refined->keys())->toContain('liquid_water')
        ->and($refined['liquid_water']->count)->toBe(1)
        ->and($crafted)->toHaveCount(1)
        ->and($crafted->keys())->toContain('crafted_cement')
        ->and($crafted['crafted_cement']->count)->toBe(5);

});

test('a less simple recipe with storage', function () {

    /*
     * crafted_concrete
     *  - crafted_cement => 5
     *       - tcargodust   => 2
     *       - refined_sand => 5
     *  - liquid_water => 1
     *       - liquid_water_raw => 1
     *       - scrap_acid       => 1
     *
     *  Storage contains 5 crafted_cement
     *
     *  expected scrap yields for 1 recipe
     *    liquid_water_raw => 1
     *    scrap_acid       => 1
     *
     *  expected refined yields for 1 recipe
     *    liquid_water     => 1
     * */
    $storage = new Storage([new \App\TT\Items\InventoryItem('crafted_cement', 5)]);
    $list = ShoppingListBuilder::build(
        RecipeFactory::get('crafted_concrete'),
        $storage,
        1,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];
    $refined = $list['refined'];

    expect($scrap)->toHaveCount(2)
        ->and($scrap->keys())->not->toContain('tcargodust')
        ->and($scrap->keys())->not->toContain('refined_sand')
        ->and($scrap->keys())->toContain('liquid_water_raw')
        ->and($scrap->keys())->toContain('scrap_acid')
        ->and($scrap['liquid_water_raw']->count)->toBe(1)
        ->and($scrap['scrap_acid']->count)->toBe(1)
        ->and($refined)->toHaveCount(1)
        ->and($refined->keys())->toContain('liquid_water')
        ->and($refined['liquid_water']->count)->toBe(1);

});

it('handles a refined recipe with a refined component', function () {

    /*
     *   $recipes['refined_amalgam'] = [
     *       'craftingLocation' => 'LS Foundry',
     *       'makes'            => 2,
     *       'components'       => [
     *           'refined_tin'   => 2,
     *           'scrap_mercury' => 2
     *       ]
     *   ];
     *
     *  expected yields for 1 recipe
     *    refined_tin    => 2
     *    scrap_mercury  => 2
     *    scrap_tin      => 4
     *
     * */

    $list = ShoppingListBuilder::build(
        RecipeFactory::get('refined_amalgam'),
        new Storage(),
        1,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];
    $refined = $list['refined'];

    expect($scrap)->toHaveCount(2)
        ->and($scrap->keys())->toContain('scrap_mercury')
        ->and($scrap->keys())->toContain('scrap_tin')
        ->and($scrap['scrap_mercury']->count)->toBe(2)
        ->and($scrap['scrap_tin']->count)->toBe(4)
        ->and($refined)->toHaveCount(1)
        ->and($refined->keys())->toContain('refined_tin')
        ->and($refined['refined_tin']->count)->toBe(2);

});

it('handles a refined recipe with a refined component and storage', function () {

    /*
     *   $recipes['refined_amalgam'] = [
     *       'craftingLocation' => 'LS Foundry',
     *       'makes'            => 2,
     *       'components'       => [
     *           'refined_tin'   => 2,
     *           'scrap_mercury' => 2
     *       ]
     *   ];
     *
     *  expected yields for 1 recipe
     *    refined_tin    => 2
     *    scrap_mercury  => 2
     *    scrap_tin      => 4
     *
     * */

    $storage = new Storage([new \App\TT\Items\InventoryItem('refined_tin', 1)]);

    $list = ShoppingListBuilder::build(
        RecipeFactory::get('refined_amalgam'),
        $storage,
        1,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];
    $refined = $list['refined'];

    expect($scrap)->toHaveCount(2)
        ->and($scrap->keys())->toContain('scrap_mercury')
        ->and($scrap->keys())->toContain('scrap_tin')
        ->and($scrap['scrap_mercury']->count)->toBe(2)
        ->and($scrap['scrap_tin']->count)->toBe(2)
        ->and($refined)->toHaveCount(1)
        ->and($refined->keys())->toContain('refined_tin')
        ->and($refined['refined_tin']->count)->toBe(1);

});

test('bug fix for recipe yields not being accounted for', function () {

    /*
     *   $recipes['crafted_rebar'] = [
     *       'craftingLocation' => 'LS Factory',
     *       'makes'            => 2,
     *       'components'       => [
     *           'refined_amalgam' => 6,
     *           'refined_bronze'  => 2
     *       ]
     *   ];
     *
     *   $recipes['refined_amalgam'] = [
     *       'craftingLocation' => 'LS Foundry',
     *       'makes'            => 2,
     *       'components'       => [
     *           'refined_tin'   => 2,
     *           'scrap_mercury' => 2
     *       ]
     *   ];
     *
     *   $recipes['refined_bronze'] = [
     *       'craftingLocation' => 'LS Foundry',
     *       'makes'            => 2,
     *       'components'       => [
     *           'scrap_aluminum' => 1,
     *           'scrap_copper'   => 2,
     *           'scrap_tin'      => 1
     *       ]
     *   ];
     *
     *  expected yields for 1 recipe with 1 amalgam in storage
     *    refined_amalgam => 6
     *    refined_bronze  => 2
     *    refined_tin     => 6
     *    scrap_mercury   => 6
     *    scrap_aluminum  => 1
     *    scrap_copper    => 2
     *    scrap_tin       => 1+12
     *
     * */

    $storage = new Storage([new \App\TT\Items\InventoryItem('refined_amalgam', 1)]);
    $storage = new Storage();

    $list = ShoppingListBuilder::build(
        RecipeFactory::get('crafted_rebar'),
        $storage,
        2,
        1000
    );

    /** @var Collection $scrap */
    $scrap = $list['scrap'];
    $refined = $list['refined'];

    expect($refined['refined_amalgam']->count)->toBe(6)
        ->and($refined['refined_bronze']->count)->toBe(2)
        ->and($refined['refined_tin']->count)->toBe(6)
        ->and($scrap['scrap_mercury']->count)->toBe(6)
        ->and($scrap['scrap_aluminum']->count)->toBe(1)
        ->and($scrap['scrap_copper']->count)->toBe(2)
        ->and($scrap['scrap_tin']->count)->toBe(13)
        ;

});

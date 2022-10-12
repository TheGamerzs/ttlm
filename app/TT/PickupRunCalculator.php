<?php

namespace App\TT;

use App\TT\Items\Item;
use App\TT\Items\ItemData;
use App\TT\Items\Weights;
use Illuminate\Support\Arr;

class PickupRunCalculator
{
    public array $neededCounts = [];

    public array $baseItemsCounts = [];

    public array $baseItemsCosts = [];

    public int $truckCapacity;

    public Storage $storage;

    public function __construct(int $truckCapacity, Storage $storage)
    {
        $this->truckCapacity = $truckCapacity;
        $this->storage = $storage;
    }

    public function addNeededCount(string $itemName, int $needed): self
    {
        if (array_key_exists($itemName, $this->neededCounts)) {
            $this->neededCounts[$itemName]['needed'] += $needed;
            return $this;
        }

        $this->neededCounts[$itemName] = [
            'itemName' => $itemName,
            'needed' => $needed
        ];

        return $this;
    }

    public function getRunCalculations()
    {
        $runData = [
            'recycled_electronics' => [
                'yields' => [
                    'scrap_copper' => 8,
                    'scrap_plastic' => 12,
                    'scrap_gold' => 1,
                ],
                'processingCost' => 25000
            ],
            'recycled_waste' => [
                'yields' => [
                    'scrap_acid' => 4,
                    'scrap_lead' => 2,
                    'scrap_mercury' => 2
                ],
                'processingCost' => 15000
            ],
            'recycled_trash' => [
                'yields' => [
                    'scrap_aluminum' => 4,
                    'scrap_tin' => 4
                ],
                'processingCost' => 25000
            ],
            'recycled_rubble' => [
                'yields' => [
                    'scrap_ore' => 4,
                    'scrap_emerald' => 1,
                    'refined_flint' => 4.8, // 12 Gravel yielded from one rubble, 10 gravel yields 4 flint. 4*12/10 = 4.8
                    'refined_sand' => 7.2   // 12 Gravel yielded from one rubble, 10 gravel yields 6 sand. 6*12/10 = 7.2
                ],
                'processingCost' => 15000 + 15000 // TODO: Not entirely accurate. Will need to change to it's own method to account for gravel cost correctly.
            ],
            'petrochem_oil' => [
                'yields' => [
                    'petrochem_diesel' => 1,
                    'petrochem_kerosene' => 1,
                    'petrochem_petrol' => 2,
                ],
                'processingCost' => 10250
            ],
            'petrochem_gas' => [
                'yields' => [
                    'military_chemicals' => 2,
                    'petrochem_propane' => 2,
                    'petrochem_waste' => 1,
                ],
                'processingCost' => 10250
            ],
        ];

        $final = collect($runData)->map(function ($runData, $runName) {
            return $this->easyCalculations($runName, ...$runData);
        });
        [$final['refined_planks'], $final['tcargodust']] = $this->sawMillRun();
        $final['liquid_water_raw'] = $this->waterRun();

        return $final;
    }

    protected function easyCalculations(string $runName, array $yields, int $processingCost): int
    {
        $baseWeight = ItemData::getWeight($runName);

        $basePickupItemsNeeded = collect($this->neededCounts)
            ->whereIn('itemName', array_keys($yields))
            ->map(function ($item) use ($yields) {
                return (int) ceil( $item['needed'] / $yields[$item['itemName']] );
            })
            ->sortByDesc(function ($value) { return $value; })
            ->first();

        if ($this->storage->contains('name', $runName)) {
            $basePickupItemsNeeded -= $this->storage->firstWhere('name', $runName)->count;
        }

        $this->baseItemsCounts[$runName] = $basePickupItemsNeeded;
        $this->baseItemsCosts[$runName] = ($basePickupItemsNeeded ?? 0) * $processingCost;

        return $runsNeeded = (int) ceil(
            $basePickupItemsNeeded * $baseWeight / $this->truckCapacity
        );
    }

    protected function waterRun()
    {
        $this->baseItemsCounts['liquid_water_raw'] = 0;
        $this->baseItemsCosts['liquid_water_raw'] = 0;
        $runs = 0;

        if (array_key_exists('liquid_water_raw', $this->neededCounts)) {
            $this->baseItemsCounts['liquid_water_raw'] = $this->neededCounts['liquid_water_raw']['needed'];
            $this->baseItemsCosts['liquid_water_raw'] = $this->neededCounts['liquid_water_raw']['needed'] * 5000;

            $waterRecipe = RecipeFactory::get(new Item('liquid_water'));
            $runs = $this->neededCounts['liquid_water_raw']['needed'] / $waterRecipe->howManyRecipesCanFit($this->truckCapacity);
        }
        return (int) ceil($runs);
    }

    protected function sawMillRun(): array
    {
        $plankRuns = 0;
        $sawdustRuns = 0;
        $logsFitInTrailer = (int) floor( $this->truckCapacity / $logWeight = 60 );

        $this->baseItemsCosts['refined_planks'] = 0;
        $this->baseItemsCosts['tcargodust'] = 0;
        $this->baseItemsCounts['logs'] = 0;

        // Find out planks
        if (array_key_exists('refined_planks', $this->neededCounts)) {
            $this->baseItemsCounts['logs'] = $this->neededCounts['refined_planks']['needed'];
            $this->baseItemsCosts['refined_planks'] = $this->neededCounts['refined_planks']['needed'] * 8000;
            $plankRuns = (int) ceil($this->neededCounts['refined_planks']['needed'] / $logsFitInTrailer);
        }

        // see if plank runs cover sawdust or not
        if (array_key_exists('tcargodust', $this->neededCounts)) {
            $sawdustNeededAfterPlankRuns = $this->neededCounts['tcargodust']['needed'] - ($plankRuns * 2);
            $this->baseItemsCounts['logs'] += (int) ceil($sawdustNeededAfterPlankRuns / 10);
            $this->baseItemsCosts['tcargodust'] = (int) ($sawdustNeededAfterPlankRuns / 10 * 8000);
            if ($sawdustNeededAfterPlankRuns > 0) {
                $sawdustRuns = (int) ceil($sawdustNeededAfterPlankRuns / 10 / $logsFitInTrailer);
            }
        }

        return [$plankRuns, $sawdustRuns];
    }

}

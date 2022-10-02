<?php

namespace App\TT;

use App\TT\Items\Item;
use Illuminate\Support\Arr;

class PickupRunCalculator
{
    public array $counts = [];

    public array $baseItemsCosts = [];

    public int $truckCapacity;

    public function __construct(int $truckCapacity)
    {
        $this->truckCapacity = $truckCapacity;
    }

    public function addNeededCount(string $itemName, int $needed): self
    {
        if (array_key_exists($itemName, $this->counts)) {
            $this->counts[$itemName]['needed'] += $needed;
            return $this;
        }

        $this->counts[$itemName] = [
            'itemName' => $itemName,
            'needed' => $needed
        ];

        return $this;
    }

    public function getRunCalculations()
    {
        $runData = [
            'electronics' => [
                'yields' => [
                    'scrap_copper' => 8,
                    'scrap_plastic' => 12,
                    'scrap_gold' => 1,
                ],
                'baseWeight' => 130,
                'processingCost' => 25000
            ],
            'toxic waste' => [
                'yields' => [
                    'scrap_acid' => 4,
                    'scrap_lead' => 2,
                    'scrap_mercury' => 2
                ],
                'baseWeight' => 110,
                'processingCost' => 15000
            ],
            'trash' => [
                'yields' => [
                    'scrap_aluminum' => 4,
                    'scrap_tin' => 4
                ],
                'baseWeight' => 110,
                'processingCost' => 25000
            ],
            'quarry' => [
                'yields' => [
                    'scrap_ore' => 4,
                    'scrap_emerald' => 1,
                    'refined_flint' => 4.8, // 12 Gravel yielded from one rubble, 10 gravel yields 4 flint. 4*12/10 = 4.8
                    'refined_sand' => 7.2   // 12 Gravel yielded from one rubble, 10 gravel yields 6 sand. 6*12/10 = 7.2
                ],
                'baseWeight' => 150,
                'processingCost' => 15000 + 15000 // TODO: Not entirely accurate. Will need to change to it's own method to account for gravel cost correctly.
            ],
            'crude oil' => [
                'yields' => [
                    'petrochem_diesel' => 1,
                    'petrochem_kerosene' => 1,
                    'petrochem_petrol' => 2,
                ],
                'baseWeight' => 150,
                'processingCost' => 10250
            ],
            'raw gas' => [
                'yields' => [
                    'military_chemicals' => 2,
                    'petrochem_propane' => 2,
                    'petrochem_waste' => 1,
                ],
                'baseWeight' => 150,
                'processingCost' => 10250
            ],
        ];

        $final = collect($runData)->map(function ($runData, $runName) {
            return $this->easyCalculations($runName, ...$runData);
        });
        [$final['planks'], $final['sawdust']] = $this->sawMillRun();
        $final['water'] = $this->waterRun();

        return $final;
    }

    protected function easyCalculations(string $runName, array $yields, int $baseWeight, int $processingCost): int
    {
        $basePickupItemsNeeded = collect($this->counts)
            ->whereIn('itemName', array_keys($yields))
            ->map(function ($item) use ($yields) {
                return (int) ceil( $item['needed'] / $yields[$item['itemName']] );
            })
            ->sortByDesc(function ($value) { return $value; })
            ->first();

        $this->baseItemsCosts[$runName] = ($basePickupItemsNeeded ?? 0) * $processingCost;

        return $runsNeeded = (int) ceil(
            $basePickupItemsNeeded * $baseWeight / $this->truckCapacity
        );
    }

    protected function waterRun()
    {
        $this->baseItemsCosts['water'] = 0;
        $runs = 0;
        if (array_key_exists('liquid_water_raw', $this->counts)) {
            $this->baseItemsCosts['water'] = $this->counts['liquid_water_raw']['needed'] * 5000;
            $waterRecipe = RecipeFactory::get(new Item('liquid_water'));
            $runs = $this->counts['liquid_water_raw']['needed'] / $waterRecipe->howManyCanFit($this->truckCapacity);
        }
        return (int) ceil($runs);
    }

    protected function sawMillRun(): array
    {
//        'tcargodust' => (int)(10 * $pickupCount),

//        'tcargodust'     => (int)(2 * $pickupCount),
//        'refined_planks' => (int)($pickupCount)

        $plankRuns = 0;
        $sawdustRuns = 0;
        $logsFitInTrailer = (int) floor( $this->truckCapacity / $logWeight = 60 );
        $this->baseItemsCosts['planks'] = 0;
        $this->baseItemsCosts['sawdust'] = 0;

        // Find out planks
        if (array_key_exists('refined_planks', $this->counts)) {
            $this->baseItemsCosts['planks'] = $this->counts['refined_planks']['needed'] * 8000;
            $plankRuns = (int) ceil($this->counts['refined_planks']['needed'] / $logsFitInTrailer);
        }

        // see if plank runs cover sawdust or not
        if (array_key_exists('tcargodust', $this->counts)) {
            $sawdustNeededAfterPlankRuns = $this->counts['tcargodust']['needed'] - ($plankRuns * 2);
            $this->baseItemsCosts['sawdust'] = (int) ($sawdustNeededAfterPlankRuns / 10 * 8000);
            if ($sawdustNeededAfterPlankRuns > 0) {
                $sawdustRuns = (int) ceil($sawdustNeededAfterPlankRuns / 10 / $logsFitInTrailer);
            }
        }

        return [$plankRuns, $sawdustRuns];
    }

}

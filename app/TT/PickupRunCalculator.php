<?php

namespace App\TT;

use App\TT\Items\Item;
use Illuminate\Support\Arr;

class PickupRunCalculator
{
    public array $counts = [];

    public int $truckCapacity;

    public function __construct(int $truckCapacity)
    {
        $this->truckCapacity = $truckCapacity;
    }

    public function addCount(string $itemName, int $needed): self
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
        $runs = [
            'electronics' => [
                'yields' => [
                    'scrap_copper' => 8,
                    'scrap_plastic' => 12,
                    'scrap_gold' => 1,
                ],
                'baseWeight' => 130
            ],
            'toxic waste' => [
                'yields' => [
                    'scrap_acid' => 4,
                    'scrap_lead' => 2,
                    'scrap_mercury' => 2
                ],
                'baseWeight' => 110
            ],
            'trash' => [
                'yields' => [
                    'scrap_aluminum' => 4,
                    'scrap_tin' => 4
                ],
                'baseWeight' => 110
            ],
            'quarry' => [
                'yields' => [
                    'scrap_ore' => 4,
                    'scrap_emerald' => 1,
                    'refined_flint' => 4.8, // 12 Gravel yielded from one rubble, 10 gravel yields 4 flint. 4*12/10 = 4.8
                    'refined_sand' => 7.2   // 12 Gravel yielded from one rubble, 10 gravel yields 6 sand. 6*12/10 = 7.2
                ],
                'baseWeight' => 150
            ]
        ];

        $final = collect($runs)->map(function ($runData) {
            return $this->easyCalculations(...$runData);
        });
        [$final['planks'], $final['sawdust']] = $this->sawMillRun();
        $final['water'] = $this->waterRun();

        return $final;
    }

    protected function waterRun()
    {
        $runs = 0;
        if (array_key_exists('liquid_water_raw', $this->counts)) {
            $waterRecipe = RecipeFactory::get(new Item('liquid_water'));
            $runs = $this->counts['liquid_water_raw']['needed'] / $waterRecipe->howManyCanFit($this->truckCapacity);
        }
        return (int) ceil($runs);
    }

    protected function easyCalculations(array $yields, int $baseWeight): int
    {
        $basePickupItemsNeeded = collect($this->counts)
            ->whereIn('itemName', array_keys($yields))
            ->map(function ($item) use ($yields) {
                return (int) ceil( $item['needed'] / $yields[$item['itemName']] );
            })
            ->sortByDesc(function ($value) { return $value; })
            ->first();

        return $runsNeeded = (int) ceil(
            $basePickupItemsNeeded * $baseWeight / $this->truckCapacity
        );
    }

    protected function sawMillRun(): array
    {
//        'tcargodust' => (int)(10 * $pickupCount),

//        'tcargodust'     => (int)(2 * $pickupCount),
//        'refined_planks' => (int)($pickupCount)

        $plankRuns = 0;
        $sawdustRuns = 0;
        $logsFitInTrailer = (int) floor( $this->truckCapacity / $logWeight = 60 );

        // find out planks
        if (array_key_exists('refined_planks', $this->counts)) {
            $plankRuns = (int) ceil($this->counts['refined_planks']['needed'] / $logsFitInTrailer);
        }

        // see if plank runs cover sawdust or not
        if (array_key_exists('tcargodust', $this->counts)) {
            $sawdustNeededAfterPlankRuns = $this->counts['tcargodust']['needed'] - ($plankRuns * 2);
            if ($sawdustNeededAfterPlankRuns > 0) {
                $sawdustRuns = (int) ceil($this->counts['tcargodust']['needed'] / 10 / $logsFitInTrailer);
            }
        }

        return [$plankRuns, $sawdustRuns];
    }

}

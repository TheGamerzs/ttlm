<?php

namespace App\TT\Items;

use App\TT\Recipe;
use App\TT\RecipeFactory;
use Illuminate\Support\Str;

class Item
{
    public string $name;

    public ?string $prettyName = null;

    public int $weight = 0;

    public function __construct(string $internalName)
    {
        $data = ItemData::getFromInternalName($internalName);
        $this->name   = $internalName;
        if ($data) {
            $this->weight = (int) $data->weight;
            $this->prettyName = $data->name;
        }
    }

    public function name(): string
    {
        return isset($this->prettyName)
            ? $this->formatPrettyName()
            : str($this->name)->title();
    }

    protected function formatPrettyName()
    {
        $prettyName = Str::of($this->prettyName);

        if ($prettyName->startsWith(['Blessing:', 'EXP Bonus:', 'Job Card:'])) {
            return $this->prettyName;
        }

        return $prettyName->after(': ');
    }

    public function getRecipe(): Recipe
    {
        return RecipeFactory::get($this);
    }

    public function howManyCanFitInSpace(int $capacityKG): int
    {
        if ($this->weight) {
            return floor($capacityKG / $this->weight);
        }
        return 0;
    }
}

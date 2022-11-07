<?php

namespace App\TT\Items;

use App\TT\Factories\ItemFactory;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use Illuminate\Support\Str;

class Item
{
    public string $name;

    public int $weight = 0;

    public ?string $prettyName = null;

    public function __construct(string $name, int $weight = 0, ?string $prettyName = null)
    {
        $this->name = $name;
        $this->weight = $weight;
        $this->prettyName = $prettyName;
    }

    public static function make(string $internalName): self
    {
        return ItemFactory::make($internalName);
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

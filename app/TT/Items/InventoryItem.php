<?php

namespace App\TT\Items;

class InventoryItem extends Item
{
    public int $count;

    public function __construct(string $name, int $count, int $weight = 0, ?string $prettyName = null)
    {
        parent::__construct($name, $weight, $prettyName);
        $this->count = $count;
    }

    public static function fromCraftingMaterial(CraftingMaterial $craftingMaterial, int $count): self
    {
        return new self($craftingMaterial->name, $count);
    }

    public function getTotalWeight(): int
    {
        return $this->weight * $this->count;
    }
}

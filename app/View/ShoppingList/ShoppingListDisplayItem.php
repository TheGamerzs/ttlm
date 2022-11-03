<?php

namespace App\View\ShoppingList;

use App\TT\Items\Item;

class ShoppingListDisplayItem
{
    public Item  $item;
    public int   $totalNeeded;
    public int   $stillNeeded;
    public float $runCount;

    public function __construct(Item $item, int $totalNeeded, int $stillNeeded, float $runCount = 0)
    {
        $this->item        = $item;
        $this->totalNeeded = $totalNeeded;
        $this->stillNeeded = $stillNeeded;
        $this->runCount    = $runCount;
    }

    public function internalName(): string
    {
        return $this->item->name;
    }

    public function displayName(): string
    {
        if ($this->runCount) {
            return $this->item->name() . "({$this->runCount})";
        }
        return $this->item->name();
    }
}

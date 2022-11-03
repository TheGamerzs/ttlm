<?php

namespace App\View\ShoppingList;

use App\TT\Items\Item;

class ShoppingListDisplayItem
{
    public Item $item;
    public int  $totalNeeded;
    public int  $stillNeeded;

    public function __construct(Item $item, int $totalNeeded, int $stillNeeded)
    {
        $this->item = $item;
        $this->totalNeeded = $totalNeeded;
        $this->stillNeeded = $stillNeeded;
    }

    public function internalName(): string
    {
        return $this->item->name;
    }

    public function displayName(): string
    {
        return $this->item->name();
    }
}

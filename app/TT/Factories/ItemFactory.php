<?php

namespace App\TT\Factories;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\ExportableItem;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Items\ItemData;
use App\TT\Recipe;

class ItemFactory
{
    public static function make(string $internalName): Item
    {
        $data = ItemData::getFromInternalName($internalName);

        return new Item(
            $internalName,
            $data ? (int) $data->weight : 0,
            $data ? $data->name : null,
        );
    }

    public static function makeInventoryItem(string $internalName, int $count): InventoryItem
    {
        $data = ItemData::getFromInternalName($internalName);

        return new InventoryItem(
            $internalName,
            $count,
            $data ? (int) $data->weight : 0,
            $data ? $data->name : null,
        );
    }

    public static function makeCraftingMaterial(string $internalName, Recipe $recipe, int $recipeCount): CraftingMaterial
    {
        $data = ItemData::getFromInternalName($internalName);

        return new CraftingMaterial(
            $internalName,
            $recipe,
            $recipeCount,
            $data ? (int) $data->weight : 0,
            $data ? $data->name : null,
        );
    }

    public static function makeExportableItem(string $internalName, int $count): ExportableItem
    {
        $data = ItemData::getFromInternalName($internalName);

        return new ExportableItem(
            $internalName,
            $count,
            $data ? (int) $data->weight : 0,
            $data ? $data->name : null,
        );
    }
}

<?php

namespace App\Listeners;

use App\Events\StorageUpdatedFromTT;
use App\TT\Items\InventoryItem;
use App\TT\StorageFactory;

class CheckMarketOrdersAreStillValid
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\StorageUpdatedFromTT $event
     *
     * @return void
     */
    public function handle(StorageUpdatedFromTT $event)
    {
        if (! $event->user->auto_delist_market_orders) return;

        $combinedStorage = StorageFactory::get('combined');

        foreach ($event->user->sellOrders as $order) {
            /** @var InventoryItem $inventoryItem */
            $inventoryItem = $combinedStorage->firstWhere('name', $order->item_name)
                ?? new InventoryItem($order->item_name, 0);

            if ($order->count > $inventoryItem->count) {
                $order->delete();
            }
        }
    }
}

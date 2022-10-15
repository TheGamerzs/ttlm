<?php

namespace App\Providers;

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
     * @param  \App\Providers\StorageUpdatedFromTT  $event
     * @return void
     */
    public function handle(StorageUpdatedFromTT $event)
    {
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

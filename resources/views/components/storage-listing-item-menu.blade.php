<div class="btn-group">
    <a type="button" class="" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a href="{{ route('marketOrders', ['itemFilter' => $inventoryItem->name]) }}"
               class="dropdown-item"
               type="button">

                View All Market Orders
            </a>
        </li>
        <li>
            <a href="{{ route('marketOrders', ['itemFilter' => $inventoryItem->name, 'type' => 'buy']) }}"
               class="dropdown-item"
               type="button">

                View Buy Orders
            </a>
        </li>
        <li>
            <a href="{{ route('marketOrders', ['itemFilter' => $inventoryItem->name, 'type' => 'sell']) }}"
               class="dropdown-item"
               type="button">

                View Sell Orders
            </a>
        </li>
    </ul>
</div>

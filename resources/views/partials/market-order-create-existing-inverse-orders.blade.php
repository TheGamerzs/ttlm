<?php
/** @var \App\Models\MarketOrder $order */
/** @var \App\Models\MarketOrder $marketOrder */
?>
<div class="text-center">
    <h4>
        {{ $inverseOrders->count() }} Existing {{ str($this->inverseType())->title()  }}
        {{ $inverseOrders->count() > 1 ? 'Orders' : 'Order' }}

        <a href="{{ route('marketOrders', ['type' => $this->inverseType(), 'itemFilter' => $marketOrder->item_name]) }}"
           class="ms-1" title="View Full Details"
           target="_blank">

            <i class="ms-1 bi bi-box-arrow-up-right"></i>
        </a>
    </h4>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Count</th>
                <th>Price Each</th>
                <th>
                    {{ $this->inverseType() == 'sell' ? 'Storage' : 'Price All' }}
                </th>
                <th></th>
            </tr>
        </thead>
        @foreach($inverseOrders as $order)
            <tr>
                <td>
                    {{ $order->count }}
                </td>
                <td>
                    ${{ number_format($order->price_each) }}
                </td>
                <td>
                    {{ $marketOrder->type == 'sell' ? '$'. number_format($order->total_cost) : $order->storage_name }}
                </td>
                <td>
                    <x-discord-profile-link-logo :user="$order->user" />
                </td>
            </tr>
        @endforeach
    </table>
</div>

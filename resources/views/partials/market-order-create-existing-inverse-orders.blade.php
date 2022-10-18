<?php
/** @var \App\Models\MarketOrder $order */
?>
<div class="text-center">
    <h4>
        {{ $inverseOrders->count() }} Existing {{ $marketOrder->type == 'sell' ? 'Buy' : 'Sell'  }}
        {{ $inverseOrders->count() > 1 ? 'Orders' : 'Order' }}
    </h4>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Count</th>
                <th>Price Each</th>
                <th>
                    {{ $marketOrder->type == 'sell' ? 'Price All' : 'Storage' }}
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

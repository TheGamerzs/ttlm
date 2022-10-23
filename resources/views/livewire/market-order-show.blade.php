<?php
/** @var \App\Models\MarketOrder $order */
?>
<div>
    <h1 class="text-center">{{ $user->name }}'s Market Orders</h1>

    <div class="row mt-5">

        @if($orders['buy']->count())
            <div class="col-md-6" id="buyOrders">
                <h2 class="text-center">Buying</h2>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Count</th>
                        <th>Price Each</th>
                        <th>Total/All</th>
                        <th>Preferred Storage</th>
                        <th>Alternate Storage</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders['buy'] as $order)
                        <tr>
                            <td>{{ $order->item->name() }}</td>
                            <td>{{ number_format($order->count) }}</td>
                            <td>${{ number_format($order->price_each) }}</td>
                            <td>${{ number_format($order->total_cost) }}</td>
                            <td>{{ $order->storage_name }}</td>
                            <td>{{ $order->alt_storage_name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($orders['sell']->count())
            <div class="col-md-6" id="sellOrders">
                <h2 class="text-center">Selling</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Count</th>
                        <th>Price Each</th>
                        <th>Total/All</th>
                        <th>Storage</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders['sell'] as $order)
                        <tr>
                            <td>{{ $order->item->name() }}</td>
                            <td>{{ number_format($order->count) }}</td>
                            <td>${{ number_format($order->price_each) }}</td>
                            <td>${{ number_format($order->total_cost) }}</td>
                            <td>{{ $order->storage_name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($orders['move']->count())
            <div class="col-md-6" id="moveOrders">
                <h2 class="text-center">Move Orders</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Count</th>
                        <th>Price/Kg</th>
                        <th>Total</th>
                        <th>From Storage</th>
                        <th>To Storage</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders['move'] as $order)
                        <tr>
                            <td>{{ $order->item->name() }}</td>
                            <td>{{ number_format($order->count) }}</td>
                            <td>${{ number_format($order->price_each) }}</td>
                            <td>${{ number_format($order->total_cost) }}</td>
                            <td>{{ $order->storage_name }}</td>
                            <td>{{ $order->alt_storage_name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

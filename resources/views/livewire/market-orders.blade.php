<?php
/** @var \App\Models\MarketOrder $marketOrder */
?>

<div>
    @include('partials.market-order-show-radios')

    @include('partials.market-order-show-filters')

    <table class="table">
        <thead>
            <tr>
                <td></td>
                @if($type == 'all')
                    <td class="text-center">Type</td>
                @endif
                <td class="text-center">Price Each</td>
                <td class="text-center">Count</td>
                <td class="text-center">Total Cost For All</td>
                <td class="text-center">Storage</td>
                <td class="text-center">Discord Profile</td>
            </tr>
        </thead>
        <tbody>
        @foreach($allMarketOrders as $marketOrder)
            <tr>
                <td>
                    {{ $marketOrder->item->name() }}
                </td>

                @if($type == 'all')
                <td class="text-center">
                    {{ str($marketOrder->type)->title() }}
                </td>
                @endif

                <td class="text-center">
                    ${{ number_format($marketOrder->price_each) }}
                </td>

                <td class="text-center">
                    {{ number_format($marketOrder->count) }}
                </td>

                <td class="text-center">
                    ${{ number_format($marketOrder->totalCost) }}
                </td>

                <td class="text-center">
                    {{ $marketOrder->storageName }}
                </td>

                <td class="text-center">
                    <a href="{{ $marketOrder->user->discordProfileLink }}" target="_blank">
                        <i class="bi bi-discord text-info"></i>
                    </a>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $allMarketOrders->links() }}
    </div>
</div>

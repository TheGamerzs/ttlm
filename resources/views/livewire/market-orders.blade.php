<?php
/** @var \App\Models\MarketOrder $marketOrder */
?>

<div>



    <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
        <input wire:model="type" type="radio" class="btn-check" name="btnradio" id="btnradio1" value="all" autocomplete="off" checked="">
        <label class="btn btn-outline-primary" for="btnradio1">All Orders</label>

        <input wire:model="type" type="radio" class="btn-check" name="btnradio" id="btnradio2" value="buy" autocomplete="off" checked="">
        <label class="btn btn-outline-primary" for="btnradio2">Buy Orders</label>

        <input wire:model="type" type="radio" class="btn-check" name="btnradio" id="btnradio3" value="sell" autocomplete="off" checked="">
        <label class="btn btn-outline-primary" for="btnradio3">Sell Orders</label>
    </div>

    {{ $type }}



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
                    {{ $marketOrder->type->title() }}
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
                    <a href="{{ $marketOrder->user->discord_dm_link }}" target="_blank">
                        <i class="bi bi-discord text-info"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $allMarketOrders->links() }}
    @dump($allMarketOrders)
</div>

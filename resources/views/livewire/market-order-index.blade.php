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
                @if($type == 'mine')
                    <td></td>
                @else
                    <td class="text-center">Discord Profile</td>
                @endif
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

                @if($type == 'mine')
                    <td class="text-end">
                        <i wire:click="$emit('editMarketOrder', '{{ $marketOrder->id }}')"
                           class="bi bi-pencil-fill text-warning cursor-pointer"
                           title="Edit"></i>

                        <i class="bi bi-x-circle-fill text-danger cursor-pointer"
                           wire:click="closeOrder('{{ $marketOrder->id }}')"
                           title="Close"></i>
                    </td>
                @else
                    <td class="text-center">
                        <x-discord-profile-link-logo :user="$marketOrder->user" />
                    </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $allMarketOrders->links() }}
    </div>

    <livewire:market-order-create-edit />
</div>

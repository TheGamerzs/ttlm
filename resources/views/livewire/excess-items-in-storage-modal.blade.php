<?php
/** @var \App\TT\Items\ExcessItem $excessItem */
?>

<div>
    {{-- Storage Listing Display Card --}}
    <x-card title="Excess Items" class="mt-3">
        <input wire:model.defer="count" type="text" class="form-control mb-1" placeholder="Count">
        <div class="text-center mb-1">
            <x-select-choices wire:model.defer="recipe">
                <x-select-options :items="\App\TT\Recipes::getNamesIfComponentsExist(true)" />
            </x-select-choices>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" wire:click="showModal">Show</button>
        </div>
    </x-card>

    {{-- Modal --}}
    <x-livewire-modal id="StorageListingExcess" :heading="$this->modalTitle()" class="modal-xl">
        <table class="table table-hover">
            <thead>
                <tr>
                    <td></td>
                    <td class="text-center">Excess Count</td>
                    <td class="text-center">Excess Weight</td>
                    <td class="text-center">Needed</td>
                    <td class="text-center">You Have</td>
                </tr>
            </thead>
            @foreach($excessItems as $excessItem)
            <tr>
                <td>
                    {{ $excessItem->name() }}
                </td>
                <td class="text-center">
                    {{ number_format($excessItem->inExcessCount()) }}
                </td>
                <td class="text-center">
                    {{ number_format($excessItem->inExcessWeight()) }}
                </td>
                <td class="text-center">
                    {{ number_format($excessItem->neededCount) }}
                </td>
                <td class="text-center">
                    {{ number_format($excessItem->count) }}
                </td>
            </tr>
            @endforeach
        </table>
    </x-livewire-modal>

</div>

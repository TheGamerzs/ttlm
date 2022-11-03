<?php
/** @var \App\View\ShoppingList\ShoppingListViewModel $viewModel */
/** @var \App\View\ShoppingList\ShoppingListDisplayItem $displayItem */
?>
<div class="row">
    <div class="col-12">
        <table class="table">
            <thead>
            <tr>
                <th class="fs-4">
                    {{ str($type)->title() }}
                </th>
                @foreach($viewModel->getDisplayItems($type) as $displayItem)
                    <td @class(['text-center', 'table-success' => ! $displayItem->stillNeeded]) >
                        @if(App\TT\Recipes::getNamesIfComponentsExist()->contains($displayItem->internalName() ?? ''))
                        <a href="#" wire:click.prevent="setRecipe('{{ $displayItem->internalName() }}', {{ $displayItem->totalNeeded }})">
                            {{ $displayItem->displayName() }}
                        </a>
                        @else
                            {{ $displayItem->displayName() }}
                        @endif
                    </td>
                @endforeach
            </tr>
            </thead>
            <tbody>

            <tr>
                <td>Total Needed</td>
                @foreach($viewModel->getDisplayItems($type) as $displayItem)
                    <td class="text-center cursor-normal">
                        {{ $displayItem->totalNeeded }}
                    </td>
                @endforeach
            </tr>

            <tr>
                <td>Still Needed</td>
                @foreach($viewModel->getDisplayItems($type) as $displayItem)
                    <td class="text-center cursor-normal">
                        {{ $displayItem->stillNeeded }}
                    </td>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>
</div>

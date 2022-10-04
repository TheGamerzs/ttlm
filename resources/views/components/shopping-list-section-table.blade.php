<?php
/** @var \App\TT\RecipeShoppingListDecorator $shoppingListItem */
?>
@if($fullList->keys()->contains($type))
    <div class="col-4">
        <table class="table">
            <thead>
            <tr>
                <td></td>
                <td class="text-center">Overall Needed</td>
                <td class="text-center">Still Needed</td>
            </tr>
            </thead>
            <tbody>
            @foreach($fullList[$type] as $shoppingListItem)
                <tr>
                    <td>{{ $shoppingListItem->recipe->displayName() }}</td>
                    <td class="text-center">
                        {{ $shoppingListItem->count }}<br>
                        ${{ number_format($shoppingListItem->getTotalCraftingCost()) }}
                    </td>
                    <td class="text-center">
                        @if($afterStorageList->keys()->contains($type))
                            @php
                                $stillNeededItem = $afterStorageList[$type]->firstWhere('recipeName', $shoppingListItem->recipeName);
                                $craftingCost = $stillNeededItem?->getTotalCraftingCost() ?? 0;
                            @endphp
                            {{ $stillNeededItem->count ?? 0 }}<br>
                            ${{ number_format($craftingCost) }}
                        @else
                            0
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

<div>
    <h1 class="text-center">
        <div class="row">
            <div class="col-3 offset-3">
                Shopping List for
            </div>
            <div class="col-1">
                <input class="form-control d-inline" type="text" wire:model.debounce="count" style="width: 8rem">
            </div>
            <div class="col-2">
                <x-recipe-select change-wire-model="recipeName" />
            </div>
        </div>
    </h1>
    <div class="row">
        @if($fullList->keys()->contains('crafted'))
        <div class="col-4">
            <table class="table">
                <thead>
                <tr>
                    <td></td>
                    <td>Overall Needed</td>
                    <td>Still Needed</td>
                </tr>
                </thead>
                <tbody>
                @foreach($fullList['crafted'] as $shoppingListItem)
                    <tr>
                        <td>{{ $shoppingListItem->recipeName }}</td>
                        <td>{{ $shoppingListItem->count }}</td>
                        <td>
                            @if($afterStorageList->keys()->contains('crafted'))
                                {{ $afterStorageList['crafted']->firstWhere('recipeName', $shoppingListItem->recipeName)->count ?? 0 }}
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
        @if($fullList->keys()->contains('refined'))
        <div class="col-4">
            <table class="table">
                <thead>
                <tr>
                    <td></td>
                    <td>Overall Needed</td>
                    <td>Still Needed</td>
                </tr>
                </thead>
                <tbody>
                @foreach($fullList['refined'] as $shoppingListItem)
                    <tr>
                        <td>{{ $shoppingListItem->recipeName }}</td>
                        <td>{{ $shoppingListItem->count }}</td>
                        <td>
                            @if($afterStorageList->keys()->contains('refined'))
                                {{ $afterStorageList['refined']->firstWhere('recipeName', $shoppingListItem->recipeName)->count ?? 0 }}
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
        @if($fullList->keys()->contains('scrap'))
        <div class="col-4">
            <table class="table">
                <thead>
                <tr>
                    <td></td>
                    <td>Overall Needed</td>
                    <td>Still Needed</td>
                </tr>
                </thead>
                <tbody>
                @foreach($fullList['scrap'] as $shoppingListItem)
                    <tr>
                        <td>{{ $shoppingListItem->recipeName }}</td>
                        <td>{{ $shoppingListItem->count }}</td>
                        <td>
                            @if($afterStorageList->keys()->contains('scrap'))
                                {{ $afterStorageList['scrap']->firstWhere('recipeName', $shoppingListItem->recipeName)->count ?? 0 }}
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
    </div>
</div>

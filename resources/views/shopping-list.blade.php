<?php
/** @var \App\TT\RecipeShoppingListDecorator $shoppingListItem */
?>
<x-layout title-addon="Shopping List">
    <h1 class="text-center">
        Shopping List for 300 Houses
    </h1>
    <div class="row">
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
                        <td>{{ $afterStorageList['crafted']->firstWhere('recipeName', $shoppingListItem->recipeName)->count ?? 0 }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
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
                        <td>{{ $afterStorageList['refined']->firstWhere('recipeName', $shoppingListItem->recipeName)->count ?? 0 }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
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
                        <td>{{ $afterStorageList['scrap']->firstWhere('recipeName', $shoppingListItem->recipeName)->count ?? 0 }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-layout>

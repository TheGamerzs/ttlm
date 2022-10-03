<div>
    <h4 class="text-center mb-4">** Still a WIP. Not 100% accurate, but close enough to be usable. **</h4>
    <h1 class="text-center">
        <div class="row">
            <div class="col-3 offset-3">
                Shopping List for
            </div>
            <div class="col-1">
                <input class="form-control d-inline" type="text" wire:model.debounce="count" style="width: 8rem">
            </div>
            <div class="col-2">
                <x-recipe-select change-wire-model="recipeName" :include-base-items="false" />
            </div>
        </div>
    </h1>
    <div class="text-center mt-5 mb-5">
            <h3>Total Crafting Costs: ${{ number_format($fullList['totalCost']) }} </h3>
            <h3>Remaining Crafting Costs: ${{ number_format($afterStorageList['totalCost']) }}</h3>
    </div>
    <div class="row">
        <x-shopping-list-section-table :after-storage-list="$afterStorageList" :full-list="$fullList" type="crafted" />
        <x-shopping-list-section-table :after-storage-list="$afterStorageList" :full-list="$fullList" type="refined" />
        <x-shopping-list-section-table :after-storage-list="$afterStorageList" :full-list="$fullList" type="scrap" />
        <div class="col-4">
            <table class="table">
                <thead>
                <tr>
                    <td>Pickup Items</td>
                    <td>Overall</td>
                    <td>Still Needed</td>
                </tr>
                </thead>
                <tbody>
                @foreach(collect($fullList['pickupCalculator']->baseItemsCounts)->filter() as $run => $cost)
                    <tr>
                        <td>{{ $run }}</td>
                        <td>{{ number_format($cost) }}</td>
                        <td>{{ number_format($afterStorageList['pickupCalculator']->baseItemsCounts[$run]) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-4">
            <table class="table">
                <thead>
                <tr>
                    <td>Costs from Pickups</td>
                    <td>Overall</td>
                    <td>Still Needed</td>
                </tr>
                </thead>
                <tbody>
                @foreach(collect($fullList['pickupCalculator']->baseItemsCosts)->filter() as $run => $cost)
                    <tr>
                        <td>{{ $run }}</td>
                        <td>${{ number_format($cost) }}</td>
                        <td>${{ number_format($afterStorageList['pickupCalculator']->baseItemsCosts[$run]) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

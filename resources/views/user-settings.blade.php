<x-layout title-addon="Settings">
    <div class="row">
        <div class="col-6 offset-3">
            <div class="mb-3">
                <label for="ttApiKey" class="form-label">Transport Tycoon Public API Key</label>
                <input type="text" class="form-control" id="ttApiKey">
            </div>
            <div class="mb-3">
                <label for="truckCompacity" class="form-label" title="Trailer Compacity">Current Trucking Compacity (kg)</label>
                <input type="text" class="form-control" id="truckCompacity">
            </div>
            <div class="mb-3">
                <label for="pocketCompacity" class="form-label" title="Your Personal Inventory">Current Pocket Compacity (kg)</label>
                <input type="text" class="form-control" id="pocketCompacity">
            </div>
            <div class="mb-3">
                <label for="trainYardCompacity" class="form-label">Current Train Yard Compacity (kg)</label>
                <input type="text" class="form-control" id="trainYardCompacity">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="button">Save</button>
            </div>
        </div>
    </div>
</x-layout>

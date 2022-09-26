<div class="row">
    <form wire:submit.prevent="updateUser">
        <div class="col-6 offset-3">
            <div class="mb-3">
                <label for="ttApiKey" class="form-label">Transport Tycoon Private API Key</label>
                <input type="text"
                       class="form-control @error('user.api_private_key') is-invalid @enderror"
                       id="ttApiKey"
                       wire:model="user.api_private_key">
            </div>
            <div class="mb-3">
                <label for="truckCompacity" class="form-label" title="Trailer Compacity">Current Trucking Compacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.truckCompacity') is-invalid @enderror"
                       id="truckCompacity"
                       wire:model="user.truckCompacity">
            </div>
            <div class="mb-3">
                <label for="pocketCompacity" class="form-label" title="Your Personal Inventory">Current Pocket Compacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.pocketCompacity') is-invalid @enderror"
                       id="pocketCompacity"
                       wire:model="user.pocketCompacity">
            </div>
            <div class="mb-3">
                <label for="trainYardCompacity" class="form-label">Current Train Yard Compacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.trainYardCompacity') is-invalid @enderror"
                       id="trainYardCompacity"
                       wire:model="user.trainYardCompacity">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </div>
    </form>
</div>

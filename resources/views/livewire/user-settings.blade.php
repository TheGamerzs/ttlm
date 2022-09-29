<div class="row">
        <div class="col-6 offset-3">
            <div class="mb-3">
                Transport Tycoon ID:<br>
                <div class="ms-4">
                    @if($user->tt_id)
                        <span class="fs-4">{{ $user->tt_id }}</span>
                    @else
                        @if(Cache::get($user->id . 'apiIdAttempts') < 1 || ! Cache::has($user->id . 'apiIdAttempts'))
                            Attempts to retrieve your TT account have been exhausted, contact me on discord (xxdalexx#9783)
                            to get this resolved.
                        @else
                            Could not retrieve your TT ID. Try again after linking your Discord/TT accounts in the TT Discord.<br>
                            <button class="btn btn-success" wire:click="tryToGetTTId">
                                Try Again (Attempts Remaining: {{ Cache::get($user->id . 'apiIdAttempts') }})
                            </button>
                            <br>
                            If you think this is in error, reach out to me on discord xxdalexx#9783
                        @endif
                    @endif
                </div>

            </div>
    <form wire:submit.prevent="updateUser">
            <div class="mb-3">
                <label for="ttApiKey" class="form-label">Transport Tycoon Private API Key</label>
                <input type="text"
                       class="form-control @error('user.api_private_key') is-invalid @enderror"
                       id="ttApiKey"
                       wire:model="user.api_private_key">
            </div>
            <div class="mb-3">
                <label for="truckCapacity" class="form-label" title="Trailer Capacity">Current Trucking Capacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.truckCapacity') is-invalid @enderror"
                       id="truckCapacity"
                       wire:model="user.truckCapacity">
            </div>
            <div class="mb-3">
                <label for="pocketCapacity" class="form-label" title="Your Personal Inventory">Current Pocket Capacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.pocketCapacity') is-invalid @enderror"
                       id="pocketCapacity"
                       wire:model="user.pocketCapacity">
            </div>
            <div class="mb-3">
                <label for="trainYardCapacity" class="form-label">Current Train Yard Capacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.trainYardCapacity') is-invalid @enderror"
                       id="trainYardCapacity"
                       wire:model="user.trainYardCapacity">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
        </div>
</div>

<div class="row">
        <div class="col-6 offset-3">
            @if($this->getCharges())
            <div class="mb-3">
                Charges Remaining: <br>
                <div class="ms-4 fs-4">
                    {{ $this->getCharges() }}
                </div>
            </div>
            @endif
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
                <label for="truckCapacity" class="form-label" title="Trailer Capacity">Current Trucking Capacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.truckCapacity') is-invalid @enderror"
                       id="truckCapacity"
                       wire:model="user.truckCapacity">
                @error('user.truckCapacity')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="pocketCapacity" class="form-label" title="Your Personal Inventory">Current Pocket Capacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.pocketCapacity') is-invalid @enderror"
                       id="pocketCapacity"
                       wire:model="user.pocketCapacity">
                @error('user.pocketCapacity')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="trainYardCapacity" class="form-label">Current Train Yard Capacity (kg)</label>
                <input type="text"
                       class="form-control @error('user.trainYardCapacity') is-invalid @enderror"
                       id="trainYardCapacity"
                       wire:model="user.trainYardCapacity">
                @error('user.trainYardCapacity')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="ttApiKey" class="form-label">Transport Tycoon Public API Key</label><br>
                <span class="ms-5">*Required if you have done an API Lock on your account.</span>
                <input type="text"
                       class="form-control @error('user.api_public_key') is-invalid @enderror"
                       id="ttApiKey"
                       wire:model="user.api_public_key">
                @error('user.api_public_key')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Default Trucking/Crafting Page Recipe</label>
                <x-select-choices wire:model="user.default_crafting_recipe">
                    <x-select-options :items="\App\TT\Recipes::getNamesIfComponentsExist(true)" />
                </x-select-choices>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
        </div>
</div>

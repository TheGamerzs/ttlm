<div class="row">
    <div class="col-6 offset-3">
        <div class="row">
            @if($this->getCharges())
                <div class="col">
                    Charges Remaining: <br>
                    <div class="ms-4 fs-4">
                        {{ $this->getCharges() }}
                    </div>
                </div>
            @endif
            <div class="col">
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
            </div>
        </div>

        <form wire:submit.prevent="updateUser">

            <hr>
            <p class="text-center">
                All capacities are in KG.<br>
                Ones marked with * are not required.
            </p>
            <hr>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="trailer_name" class="form-label" title="Trailer Capacity">Trailer One Name</label>
                    <input type="text"
                           class="form-control @error('user.trailer_name') is-invalid @enderror"
                           id="trailer_name"
                           wire:model="user.trailer_name">
                    @error('user.trailer_name')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="truckCapacity" class="form-label" title="Trailer Capacity">Trailer One Capacity</label>
                    <input type="text"
                           class="form-control @error('user.truckCapacity') is-invalid @enderror"
                           id="truckCapacity"
                           wire:model="user.truckCapacity">
                    @error('user.truckCapacity')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="trailer_two_name" class="form-label" title="Trailer Capacity">Trailer Two Name*</label>
                    <input type="text"
                           class="form-control @error('user.trailer_two_name') is-invalid @enderror"
                           id="trailer_two_name"
                           wire:model="user.trailer_two_name">
                    @error('user.trailer_two_name')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="truckCapacityTwo" class="form-label" title="Trailer Capacity">Trailer Two Capacity*</label>
                    <input type="text"
                           class="form-control @error('user.truckCapacity') is-invalid @enderror"
                           id="truckCapacityTwo"
                           wire:model="user.truckCapacityTwo">
                    @error('user.truckCapacityTwo')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pocketCapacity" class="form-label" title="Your Personal Inventory">Pocket Capacity</label>
                    <input type="text"
                           class="form-control @error('user.pocketCapacity') is-invalid @enderror"
                           id="pocketCapacity"
                           wire:model="user.pocketCapacity">
                    @error('user.pocketCapacity')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="trainYardCapacity" class="form-label">Train Yard Capacity*</label>
                    <input type="text"
                           class="form-control @error('user.trainYardCapacity') is-invalid @enderror"
                           id="trainYardCapacity"
                           wire:model="user.trainYardCapacity">
                    @error('user.trainYardCapacity')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <label for="ttApiKey" class="form-label">Transport Tycoon Public API Key</label><br>
                <span class="ms-3">Only required if you have done an API Lock on your account.</span>
                <input type="text"
                       class="form-control @error('user.api_public_key') is-invalid @enderror"
                       id="ttApiKey"
                       wire:model="user.api_public_key">
                @error('user.api_public_key')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label">Default Trucking/Crafting Page Recipe</label>
                <x-select-choices wire:model="user.default_crafting_recipe">
                    <x-select-options :items="\App\TT\Recipes::getNamesIfComponentsExist(true)" />
                </x-select-choices>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input wire:model="user.dark_mode" class="form-check-input" type="checkbox" role="switch" id="darkMode">
                    <label class="form-check-label" for="darkMode">Dark Mode (Refresh page after saving.)</label>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input wire:model="user.auto_delist_market_orders" class="form-check-input" type="checkbox" role="switch" id="autoDelist">
                    <label class="form-check-label" for="autoDelist">
                        Auto delist market sell orders on storage sync when you don't have enough to cover.
                    </label>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

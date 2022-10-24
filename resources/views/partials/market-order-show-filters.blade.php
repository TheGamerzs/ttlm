<x-card title="Filters">
    <div class="row">
        <div class="col-4 text-center">
            <x-card title="Item">
                <x-select-choices wire:model="itemFilter">
                    <x-select-options :items="$this->itemSelectOptions" />
                </x-select-choices>
            </x-card>
        </div>

        <div class="col-4">
            <x-card title="Count">
                <div class="row">
                    <div class="col-6">
                        <input wire:model="countMinFilter" class="form-control" type="text" placeholder="Minimum" />
                    </div>
                    <div class="col-6">
                        <input wire:model="countMaxFilter" class="form-control" type="text" placeholder="Maximum" />
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-4">
            <x-card title="Price Each">
                <div class="row">
                    <div class="col-6">
                        <input wire:model="priceMinFilter" class="form-control" type="text" placeholder="Minimum" />
                    </div>
                    <div class="col-6">
                        <input wire:model="priceMaxFilter" class="form-control" type="text" placeholder="Maximum" />
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-card>

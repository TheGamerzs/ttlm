<div class="text-center">
    <x-select-choices wire:model="itemName">
        <x-select-options :items="\App\TT\Recipes::getNamesIfComponentsExist(true)" />
    </x-select-choices>
    <div class="d-grid">
        <button wire:click="goToLink" class="btn btn-primary">Go</button>
    </div>
</div>

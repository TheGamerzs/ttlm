<x-layout title-addon="Trucking\Crafting">
    <div class="row">
        <div class="col-6">
            <livewire:parent-recipe-table
                :parent-recipe="$parentRecipe"
                :truck-capacity="$truckCapacity" />
        </div>
        <div class="col-6">
            <livewire:next-grind-revised :truck-capacity="$truckCapacity"
                                         :parent-recipe="$parentRecipe" />
        </div>
    </div>

    <hr>

    <div class="d-flex justify-content-center">
        <livewire:sync-storage-button />
    </div>


    <div class="row">
        <div class="col-12">
            <livewire:quick-inventory-calculations :truck-capacity="$truckCapacity"
                                                   :train-yard-storage="$trainYardStorage"
                                                   :pocket-capacity="$pocketCapacity" />
            <div class="row">
                <div class="col-6">
                    <livewire:storages-for-item />
                </div>
                <div class="col-6">
                    <livewire:recipes-with-full-loads :truck-capacity="$truckCapacity" />
                </div>
            </div>
        </div>
    </div>
</x-layout>


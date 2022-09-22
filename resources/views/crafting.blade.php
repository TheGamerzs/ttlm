<x-layout>
    <div class="row">
        <div class="col-6">
            <livewire:parent-recipe-table
                :parent-recipe="$parentRecipe"
                :truck-compacity="$truckCompacity" />
        </div>
        <div class="col-6">
            <livewire:next-grind :truck-compacity="$truckCompacity"
                                 :parent-recipe="$parentRecipe"/>
        </div>
    </div>

    <hr>


    <div class="row">
        <div class="col-12">
            <livewire:quick-inventory-calculations :truck-compacity="$truckCompacity" />
            <div class="row">
                <div class="col-6">
                    <livewire:storages-for-item />
                </div>
                <div class="col-6">
                    <livewire:recipes-with-full-loads :truck-compacity="$truckCompacity" />
                </div>
            </div>
            <livewire:storage-listing :truck-compacity="$truckCompacity"/>
        </div>
    </div>
</x-layout>


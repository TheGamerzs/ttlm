<div class="modal fade" id="craftingGoal" aria-modal="true" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="exampleModalXlLabel">Goal</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="input-group">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="goalCount" wire:model="goalCount"/>
                        <label for="goalCount">How Many</label>
                    </div>
                    <span class="input-group-text">
                        <i class="bi bi-info-circle" title="Set to 0 or blank to disable."></i>
                    </span>
                </div>

                <div class="text-center mt-2">
                    <x-select-choices wire:model="goalRecipe">
                        <x-select-options :items="\App\TT\Recipes::getNamesIfComponentsExist(true)" />
                    </x-select-choices>
                </div>

                <div class="d-grid mt-2">
                    <button class="btn btn-success" wire:click="updateGoal">
                        Save
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

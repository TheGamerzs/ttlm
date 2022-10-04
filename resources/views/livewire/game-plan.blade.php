<div class="modal fade" id="gamePlan" aria-modal="true" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="exampleModalXlLabel">GamePlan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form wire:submit.prevent="addItem">
                    <div class="input-group mb-3">
                        <input wire:model="newItemTextInput" type="text" class="form-control" placeholder="New...">
                        <button wire:click="addItem" class="btn btn-outline-primary" type="button">Add</button>
                    </div>
                </form>

                <ul class="list-group list-group-flush mx-3" wire:sortable="updateOrder">
                    @foreach($listItems as $index => $text)
                    <li class="list-group-item d-flex justify-content-between" wire:sortable.item="{{ $text }}" wire:key="item-{{$index}}">
                        <div class="div">
                            <i wire:sortable.handle class="bi bi-arrows-expand fs-4 text-success cursor-grab me-2"></i>
                            {{ $text }}
                        </div>
                        <div>
                            <i wire:click="updateItem({{ $index }})" class="bi bi-pencil-fill text-warning cursor-pointer"></i>
                            <i wire:click="removeItem({{ $index }})" class="bi bi-x text-danger fs-3 me-2 cursor-pointer"></i>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <div class="text-center">
                    <a wire:click.prevent="clearAllItems" class="cursor-pointer">Clear All</a>
                </div>
            </div>
        </div>
    </div>
</div>

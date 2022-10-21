<div>

    <div class="modal fade" id="MODetails" aria-modal="true" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-4">Additional Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{ $marketOrder?->item_name }}
                    {{ $marketOrder?->details }}
                </div>

            </div>
        </div>
    </div>

</div>

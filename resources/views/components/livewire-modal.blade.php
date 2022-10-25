<div class="modal fade" id="{{ $id }}" aria-modal="true" role="dialog" wire:ignore.self>
    <div {{ $attributes->merge(['class' => 'modal-dialog modal-dialog-centered']) }}>
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-4">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

        </div>
    </div>
</div>

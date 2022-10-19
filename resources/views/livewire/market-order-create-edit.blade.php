<div>
    <div class="modal fade" id="marketOrder" aria-modal="true" role="dialog" wire:ignore.self x-data="{ expand: @entangle('expand') }">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4">
                        {{ $marketOrder->exists ? 'Update' : 'Create' }}
                        Market Order
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row" x-show="expand" wire:key="inverseOrders" x-transition>
                        <div class="col-12">
                            @include('partials.market-order-create-existing-inverse-orders')
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6" wire:key="createFormLeft">
                            @include('partials.market-order-create-form-left')
                        </div>
                        <div class="col-6" wire:key="createFormRight">
                            @include('partials.market-order-create-form-right')
                        </div>
                    </div>

                    <hr>
                    <div class="d-grid">
                        <button class="btn btn-success" wire:click="save">
                            {{ $marketOrder->exists ? 'Update Listing' : 'List' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


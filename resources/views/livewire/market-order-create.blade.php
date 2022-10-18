<div>
    <div class="modal fade" id="marketOrder" aria-modal="true" role="dialog" wire:ignore.self x-data="{ expand: @entangle('expand') }">
        <div class="modal-dialog modal-dialog-centered" :class="expand ? 'modal-xl' : ''" x-transition>
            <div class="modal-content">
                <div class="modal-header" @click="expand = ! expand">
                    <h1 class="modal-title fs-4" id="exampleModalXlLabel">
                        {{ $marketOrder->exists ? 'Update' : 'Create' }}
                        Market Order
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div :class="expand ? 'col-6' : 'col-12'" wire:key="createForm">
                            @include('partials.market-order-create-form')
                        </div>
                        <div class="col-6" x-show="expand" x-transition wire:key="inverseOrders">
                            @include('partials.market-order-create-existing-inverse-orders')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


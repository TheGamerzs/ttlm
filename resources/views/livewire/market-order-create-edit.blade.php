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

                    @if($warn)
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    Warning: You currently don't have {{ $marketOrder->count }} {{ $marketOrder->item->name() }}
                                    in your {{ $marketOrder->storage_name }} storage.
                                    <br>Click list again to post anyway.
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-{{ $marketOrder->exists ? 6 : 12 }} d-grid">
                            @if($warn)
                                <button class="btn btn-warning" wire:click="save(true)">
                                    {{ $marketOrder->exists ? 'Update Listing' : 'List' }}
                                </button>
                            @else
                                <button class="btn btn-success" wire:click="save">
                                    {{ $marketOrder->exists ? 'Update Listing' : 'List' }}
                                </button>
                            @endif
                        </div>
                        @if($marketOrder->exists)
                            <div class="col-6 d-grid">
                                <button class="btn btn-danger" wire:click="confirmDelete">
                                    Delete
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="btn-group d-flex justify-content-center mb-3" role="group" aria-label="Basic radio toggle button group">
    <input wire:model="type" type="radio" class="btn-check" name="btnradio" id="btnradio1" value="all" autocomplete="off" checked="">
    <label class="btn btn-outline-primary" for="btnradio1">All Orders</label>

    <input wire:model="type" type="radio" class="btn-check" name="btnradio" id="btnradio2" value="buy" autocomplete="off" checked="">
    <label class="btn btn-outline-primary" for="btnradio2">Buy Orders</label>

    <input wire:model="type" type="radio" class="btn-check" name="btnradio" id="btnradio3" value="sell" autocomplete="off" checked="">
    <label class="btn btn-outline-primary" for="btnradio3">Sell Orders</label>

    @auth
        <input wire:model="type" type="radio" class="btn-check" name="btnradio" id="btnradio4" value="mine" autocomplete="off" checked="">
        <label class="btn btn-outline-primary" for="btnradio4">My Orders</label>
    @endauth
</div>

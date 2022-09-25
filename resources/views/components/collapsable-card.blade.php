<div class="my-3" x-data="{open: false}">
    <div class="card">
        <h5 class="card-header d-flex justify-content-between">
            {{ $title ?? '' }}
            <i @click="open = !open" x-show="!open" class="bi bi-chevron-double-down cursor-pointer"></i>
            <i @click="open = !open" x-show="open" class="bi bi-chevron-double-up cursor-pointer"></i>
        </h5>
        <div x-show="open" class="card-body" x-transition>
            {{ $slot }}
        </div>
    </div>
    <hr>
</div>

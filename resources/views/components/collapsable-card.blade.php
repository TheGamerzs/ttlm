@php
    // This seems like a stupid way to do this, was having issues with @props.
    $open = $open ?? true;
    $open = $open
        ? 'true'
        : 'false';
@endphp

<div class="my-3" x-data="{open: {{$open}} }">
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

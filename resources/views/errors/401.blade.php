<x-layout title-addon="Error">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div>
            <h2>{{ $exception->getMessage() }}</h2>
            <p class="fs-3">Transport Tycoon's API service may be down</p>
        </div>
    </div>
</x-layout>

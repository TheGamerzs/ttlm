<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @if(!App::environment('production'))DEV -@endif
        TTLM {{ $titleAddon ? '-' : '' }} {{ $titleAddon ?? '' }}
    </title>


    <link rel="stylesheet" href="{{ asset('css\lux.css') }}">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />

    @if(Auth::user()?->dark_mode)
        <link rel="stylesheet" href="{{ asset('css/dark-addon.css') }}" />
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css\app.css') }}">
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/js/app.js')
    @livewireStyles
</head>

<body>
    <x-navigation-menu />

    <livewire:alert-listener />
    <main class="container mt-5">
        @auth
            <livewire:game-plan />
        @endauth
        {{ $slot }}
    </main>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
</html>

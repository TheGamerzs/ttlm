<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        TTLM {{ $titleAddon ? '-' : '' }} {{ $titleAddon ?? '' }}
    </title>

    <link rel="stylesheet" href="{{ asset('css\lux.css') }}">
    <link rel="stylesheet" href="{{ asset('css\app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/js/app.js')
    @livewireStyles
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">TT Logistics Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('craftingPage') }}">Trucking\Crafting</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shoppingList') }}">Shopping List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('storageManagement') }}">Storage Management</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-md-0">
            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('userSettings') }}">Settings</a></li>
                    </ul>
                </li>
            @endauth
            @guest
                <li class="nav-item fs-4">
                    <a href="{{ route('discordSend') }}" class="nav-link">
                        Login <i class="bi bi-discord"></i>
                    </a>
                </li>
            @endguest
            </ul>

        </div>
    </div>
</nav>

<main class="container mt-5">
    {{ $slot }}
</main>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
</html>

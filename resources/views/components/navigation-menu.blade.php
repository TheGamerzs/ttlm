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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('marketOrders') }}">Market Orders</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link cursor-pointer" data-bs-toggle="modal" data-bs-target="#gamePlan">
                            Game Plan
                        </a>
                    </li>
                @endauth
                @if(Auth::id() == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-center">
                            <li><a class="dropdown-item" href="{{ route('admin.index') }}">Overview</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users') }}">Users</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav mb-2 mb-md-0">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-center">
                            <li><a class="dropdown-item" href="{{ route('userSettings') }}">Settings</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
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

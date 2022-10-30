<x-layout title-addon="Admin - Users">
    <h1 class="text-center mb-5">
        Users ({{ $users->count() }})
    </h1>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>User</th>
                <th class="text-center">Last Seen</th>
                <th class="text-center">Calls</th>
                <th class="text-center">Market Orders</th>
                <th class="text-center">Using Dark Mode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    {{ $user->id }}
                </td>

                <td>
                    {{ $user->name }}
                </td>

                <td class="text-center">
                    {{ $user->updated_at->diffForHumans() }}
                </td>

                <td class="text-center">
                    {{ $user->calls_made }}
                </td>

                <td class="text-center">
                    {{ $user->market_orders_count ?? 0 }}
                </td>

                <td class="text-center">
                    @if($user->dark_mode)
                        <i class="bi bi-check-lg text-success"></i>
                    @else
                        <i class="bi bi-x-lg text-danger"></i>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>

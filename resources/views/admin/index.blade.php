<x-layout title-addon="Admin">
    <div class="row">
        <div class="col-6">
            <x-card title="Users">
                <p class="text-center">
                    {{ $userCount }}
                </p>
            </x-card>
        </div>
        <div class="col-6">
            <x-card title="Market Orders">
                <p class="text-center">
                    {{ $marketOrderCount }}
                </p>
            </x-card>
        </div>
    </div>
</x-layout>

<div>
    <table class="table table-sm table-hover">
        <tr>
            <th></th>
            <th></th>
            <th>Weights</th>
            <th>Names</th>
            <th></th>
        </tr>
        @foreach($items as $itemName)
            <tr>
                <td>{{ $itemName }}</td>
                <td>
                    <a target="_blank" href="https://ttapi.elfshot.xyz/items?item={{ $itemName }}">Elfshot Lookup</a>
                </td>
                <td>
                    @if(array_key_exists($itemName, \App\TT\Items\Weights::$weights))
                        <i class="bi bi-clipboard2-check-fill text-success"></i>
                    @endif
                </td>
                <td>
                    @if(array_key_exists($itemName, \App\TT\Items\ItemNames::$names))
                        <i class="bi bi-clipboard2-check-fill text-success"></i>
                    @endif
                </td>
                <td>
                    <a href="#" wire:click.prevent="addToIgnore('{{ $itemName }}')">Ignore</a> |
                    <a href="#" wire:click.prevent="deleteFromAll('{{ $itemName }}')">Delete</a>
                </td>
            </tr>
        @endforeach
    </table>
    <div class="d-flex justify-content-center">
        <button wire:click="clearIgnore" class="btn btn-primary">Clear Ignored List</button>
        <button wire:click="clearAll" class="btn btn-primary ms-2">Delete All</button>
    </div>
</div>

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
                    {{ array_key_exists($itemName, \App\TT\Items\Weights::$weights) }}
                </td>
                <td>
                    {{ array_key_exists($itemName, \App\TT\Items\ItemNames::$names) }}
                </td>
                <td>
                    <a href="#" wire:click.prevent="addToIgnore('{{ $itemName }}')">Ignore</a>
                </td>
            </tr>
        @endforeach
    </table>
</div>

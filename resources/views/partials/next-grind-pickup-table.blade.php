<h5 class="text-center mt-5">{{ $this->getRecipe()->pickupRun }} Run Yields</h5>
@foreach($this->pickupRunYields() as $runPossibility)
    <table class="table">
        <thead>
        <tr>
            <td></td>
            <td>One</td>
            <td>Two</td>
            <td>Three</td>
        </tr>
        </thead>
        <tbody>
        @foreach($runPossibility as $materialName => $count)
            <tr>
                <td>{{ \App\TT\Items\ItemData::getName($materialName) }}</td>
                <td>{{ $count }}</td>
                <td>{{ $count * 2 }}</td>
                <td>{{ $count * 3 }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p class="text-center">
        {{ max( (int)ceil(($this->getParentRecipeCountForFullTrailer() - $this->getParentRecipe()->getComponent($this->getRecipe()->internalName())->inStorage) / $runPossibility[$this->getRecipe()->internalName()]), 0) }}
        Runs Required
        for {{ $this->getParentRecipe()->howManyRecipesCanFit($truckCapacity) }} {{ $this->getParentRecipe()->displayName() }}s
    </p>
@endforeach

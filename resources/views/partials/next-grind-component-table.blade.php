<table class="table table-hover text-center">
    <thead>
    <tr>
        <th scope="col" style="width: 15rem;"></th>
        @foreach($this->getRecipe()->components as $craftingMaterial)
            <th>{{ $craftingMaterial->name() }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row">
            Recipe
        </th>
        @foreach($this->getRecipe()->components as $craftingMaterial)
            <td>{{ $craftingMaterial->recipeCount }}</td>
        @endforeach
    </tr>
    <tr>
        <th scope="row">
            In Storage
        </th>
        @foreach($this->getRecipe()->components as $craftingMaterial)
            <td>{{ $craftingMaterial->inStorage }}</td>
        @endforeach
    </tr>
    <tr>
        <th scope="row">
            Fill Trailer
            <span title="How many full trailers worth you currently have in storage.">
                            ({{ $this->getRecipe()->howManyFullLoadsFromStorage($truckCapacity) }})
                        </span>
        </th>
        @foreach($this->getRecipe()->components as $craftingMaterial)
            <td>{{ $craftingMaterial->howManyCanFitInSpace($truckCapacity) }}</td>
        @endforeach
    </tr>
    @if($this->usingGoal())
        <tr>
            <th scope="row">
                Needed For Goal ({{ $this->goalCount }})
            </th>
            @foreach($this->getNeededForGoal() as $count)
                <td>{{ $count }}</td>
            @endforeach
        </tr>
    @endif
    <tr>
        <th scope="row">
            One Trailer Run of {{ $this->getParentRecipe()->displayName() }}
            ({{ $this->getParentRecipeCountForFullTrailer() }})
        </th>
        @foreach($this->getNeededForParentTrailer() as $count)
            <td>{{ $count }}</td>
        @endforeach
    </tr>
    <tr>
        <th scope="row">
            <div class="form-floating">
                <input type="text" class="form-control text-end" id="customCount" wire:model="customCount" />
                <label for="customCount">Custom Count</label>
            </div>
        </th>
        @foreach($this->getRecipe()->components as $craftingMaterial)
            <td>{{ (int)$customCount * $craftingMaterial->recipeCount / $craftingMaterial->recipe->makes }}</td>
        @endforeach
    </tr>
    </tbody>
</table>

@if($shouldLink)
<a href="{{ route('craftingPage', ['name' => $craftingMaterial->name]) }}">
@endif

    @if($showRecipeCount)
        ({{ $craftingMaterial->recipeCount }})
    @endif
    {{ $craftingMaterial->name() }}

@if($shouldLink)
</a>
@endif

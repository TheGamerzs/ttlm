@if($shouldLink)
<a href="{{ route('craftingPage', ['name' => $craftingMaterial->name]) }}">
@endif

    ({{ $craftingMaterial->recipeCount }}) {{ $craftingMaterial->name }}

@if($shouldLink)
</a>
@endif

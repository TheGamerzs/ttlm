<div
    x-data="{
        model: @entangle($attributes->wire('model')),
        select2: null
    }"
    x-init="
        select2 = new Choices($refs.select, {
                position: 'bottom',
                searchPlaceholderValue: '{{__('Search...')}}',
                shouldSort: false,
                itemSelectText: ''
            })
        $refs.select.addEventListener('change', (event) => {
            if (event.target.hasAttribute('multiple')){
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value);
            }else{
                model = event.target.value;
            }
        });
        $watch('model', value => {
            select2.setChoiceByValue(value);
        });
    "
    wire:ignore
>
    <select x-ref="select" {{ $attributes->merge(['class' => '']) }}>
        {{ $slot }}
    </select>
</div>

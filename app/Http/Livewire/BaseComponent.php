<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BaseComponent extends Component
{
    // Override livewires method to fix annoying array issue with alpine.
    public function syncInput($name, $value, $rehash = true)
    {
        // Alpine? is causing livewire to send two actions to update a property, one via syncInput and one via call method $set.
        // The first one via syncInput is passing in an array ['value' => 'updated_value"] and setting the array to the
        // component property, which breaks anything strict typed to a string.

        // Here were going to intercept that first call with the array and throw it away.
        if (is_array($value) && count($value) == 1 && array_key_exists('value', $value)) {
            // Do nothing with the garbage call, if statement written this way for readability.
        } else {
            parent::syncInput($name, $value, $rehash);
        }

    }
}

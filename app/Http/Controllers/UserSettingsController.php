<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserSettingsController
{
    public function index()
    {
        return view('user-settings-page')->with([
            'user' => Auth::user()
        ]);
    }
}

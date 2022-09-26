<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasSettingsForAPI
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()->canMakeApiCall()) {
            return redirect()->route('userSettings');
            //Session an alert
        }
        return $next($request);
    }
}

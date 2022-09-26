<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureUserHasSettingsForAPI
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()->canMakeApiCall()) {
            Session::flash('failedApiAlert', true);
            return redirect()->route('userSettings');
        }
        return $next($request);
    }
}

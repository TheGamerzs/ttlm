<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureUserHasCapacitiesSet
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()->canCalculate()) {
            Session::flash('noCapacitiesSetAlert', true);
            return redirect()->route('userSettings');
        }

        return $next($request);
    }
}

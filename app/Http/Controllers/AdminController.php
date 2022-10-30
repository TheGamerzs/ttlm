<?php

namespace App\Http\Controllers;

use App\Models\MarketOrder;
use App\Models\User;

class AdminController
{
    public function index()
    {
        return view('admin.index')
            ->with([
                'userCount' => User::count(),
                'marketOrderCount' => MarketOrder::count()
            ]);
    }

    public function users()
    {
        return view('admin.users')
            ->with([
                'users' => User::orderByDesc('updated_at')->withCount('marketOrders')->get()
            ]);
    }
}

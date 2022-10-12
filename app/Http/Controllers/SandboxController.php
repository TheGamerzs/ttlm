<?php

namespace App\Http\Controllers;

use App\TT\Items\ItemData;

class SandboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('onlyUserOne');
    }

    public function index()
    {
        $dump = ItemData::getAllInternalTruckingNames();
        dump($dump);
    }
}

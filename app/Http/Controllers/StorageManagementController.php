<?php

namespace App\Http\Controllers;

class StorageManagementController
{
    public function index(string $name = 'combined')
    {
        return view('storageManagement')->with([
            'storageName' => $name
        ]);
    }
}

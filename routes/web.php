<?php

use App\Http\Controllers\DiscordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
});

Route::get('/crafting/{name?}', [\App\Http\Controllers\CraftingController::class, 'index'])->name('craftingPage');
Route::get('/shopping-list/{name?}', [\App\Http\Controllers\ShoppingListController::class, 'index'])->name('shoppingList');
Route::get('/storages/{name?}', [\App\Http\Controllers\StorageManagementController::class, 'index'])->name('storageManagement');

Route::get('/sb', [\App\Http\Controllers\SandboxController::class, 'index']);


Route::get('/auth/redirect', [DiscordController::class, 'redirectToDiscord'])->name('discordSend');
Route::get('/auth/callback', [DiscordController::class, 'handleCallback'])->name('discordCallback');

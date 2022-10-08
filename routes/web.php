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
    if (Auth::check()) {
        return view('home');
    }
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'ttApi', 'canCalculate'])->group(function () {
    Route::get('/crafting/{name?}', [\App\Http\Controllers\CraftingController::class, 'index'])->name('craftingPage');
    Route::get('/shopping-list', [\App\Http\Controllers\ShoppingListController::class, 'index'])->name('shoppingList');
    Route::get('/storages/{name?}', [\App\Http\Controllers\StorageManagementController::class, 'index'])->name('storageManagement');
});
Route::get('/settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('userSettings')->middleware('auth');
Route::get('/market-orders', \App\Http\Livewire\MarketOrders::class)->name('marketOrders');

Route::view('login', 'discord-login-cta')->name('login');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');

Route::get('/auth/redirect', [DiscordController::class, 'redirectToDiscord'])->name('discordSend');
Route::get('/auth/callback', [DiscordController::class, 'handleCallback'])->name('discordCallback');

Route::get('/sb', [\App\Http\Controllers\SandboxController::class, 'index']);

Route::get('/dev/missing-items', \App\Http\Livewire\MissingItems::class)
    ->name('missingItems')
    ->middleware(['auth', 'onlyUserOne']);

Route::get('/dev/loginas/{id}', function (int $id) {
    if (Auth::id() != 1) abort(404);
    Auth::loginUsingId($id);
    return redirect()->back();
});

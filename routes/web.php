<?php

use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\ItemBarcodePrintController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    })->name('home');

    Route::get('/oauth', [SsoController::class, 'redirect'])->name('oauth.login');
    Route::get('/oauth/callback', [SsoController::class, 'callback'])->name('oauth.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/items/barcodes', ItemBarcodePrintController::class)
        ->name('items.barcodes.print');
});

require __DIR__.'/settings.php';

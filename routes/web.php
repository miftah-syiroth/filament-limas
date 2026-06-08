<?php

use App\Http\Controllers\Auth\SsoController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    })->name('home');

    Route::get('/oauth', [SsoController::class, 'redirect'])->name('oauth.login');
    Route::get('/oauth/callback', [SsoController::class, 'callback'])->name('oauth.callback');
});

require __DIR__.'/settings.php';

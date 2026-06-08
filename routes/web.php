<?php

use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    })->name('home');
});

require __DIR__.'/settings.php';

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check() && Auth::user()?->hasRole(['admin', 'super_admin'])) {
        return redirect('/admin');
    }

    return redirect('/app');
})->name('home');

require __DIR__.'/settings.php';

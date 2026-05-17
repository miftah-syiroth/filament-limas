<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
        return redirect('/admin');
    }

    abort(403, 'Unauthorized action.');
})->name('home');

require __DIR__.'/settings.php';

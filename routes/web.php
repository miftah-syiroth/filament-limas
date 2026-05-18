<?php

use App\Support\Auth\PostAuthenticationRedirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect(PostAuthenticationRedirect::pathFor(Auth::user()));
    }

    return redirect()->route('login');
})->name('home');

require __DIR__.'/settings.php';

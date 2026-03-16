<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/activity-logs/{activityLog}/subject-json', function (ActivityLog $activityLog) {
        $data = $activityLog->subject?->toArray() ?? [];

        return response()->json($data, 200, ['Content-Type' => 'application/json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    })->name('admin.activity-logs.subject-json');
});

require __DIR__.'/settings.php';

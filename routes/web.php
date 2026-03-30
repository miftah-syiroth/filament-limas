<?php

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/activity-logs/{activityLog}', function (ActivityLog $activityLog, Request $request) {
        // dd($activityLog->subject, $activityLog->properties);

        if ($request->data === 'subject') {
            $data = $activityLog->subject?->toArray() ?? null;
        } else if ($request->data === 'properties') {
            // $data = $activityLog->properties?->toArray() ?? null;
            $data = json_decode($activityLog->properties, true) ?? null;
        }

        if (is_null($data)) {
            return response('', 204);
        }
        
        if (is_array($data)) {
            return response()->json($data, 200, ['Content-Type' => 'application/json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        dd(false);
    })->name('admin.activity-logs.show');
});

require __DIR__.'/settings.php';

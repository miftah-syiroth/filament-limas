<?php

use App\Filament\Resources\ActivityLogs\Pages\ManageActivityLogs;
use App\Models\ActivityLog;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Actions\ViewAction;
use Livewire\Livewire;

test('activity log view action opens read-only modal', function () {
    $this->actingAs(User::factory()->create());

    $log = ActivityLog::query()->create([
        'description' => 'Test activity description',
        'log_name' => 'default',
    ]);

    Livewire::test(ManageActivityLogs::class)
        ->assertCanSeeTableRecords([$log])
        ->callAction(TestAction::make(ViewAction::class)->table($log))
        ->assertOk();
});

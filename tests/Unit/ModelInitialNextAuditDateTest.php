<?php

use App\Models\Model as InventoryModel;
use Illuminate\Support\Carbon;

it('returns null when audit interval is not set', function (): void {
    $model = new InventoryModel(['audit_interval' => null]);

    expect($model->computeInitialNextAuditDate())->toBeNull();
});

it('returns null when audit interval is zero', function (): void {
    $model = new InventoryModel(['audit_interval' => 0]);

    expect($model->computeInitialNextAuditDate())->toBeNull();
});

it('returns now plus audit interval in months', function (): void {
    Carbon::setTestNow('2026-05-18 10:00:00');

    $model = new InventoryModel(['audit_interval' => 12]);

    expect($model->computeInitialNextAuditDate()?->toDateTimeString())
        ->toBe('2027-05-18 10:00:00');
})->after(function (): void {
    Carbon::setTestNow();
});

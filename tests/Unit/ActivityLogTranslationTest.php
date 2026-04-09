<?php

uses(Tests\TestCase::class);

test('activity log translation keys resolve in English and Indonesian', function (): void {
    expect(trans('activitylog.model_label', [], 'en'))->toBe('Activity log');
    expect(trans('activitylog.plural_model_label', [], 'en'))->toBe('Activity logs');
    expect(trans('activitylog.navigation_label', [], 'en'))->toBe('Activity logs');
    expect(trans('activitylog.table.subject_type', [], 'en'))->toBe('Subject type');
    expect(trans('activitylog.infolist.description', [], 'en'))->toBe('Description');

    expect(trans('activitylog.navigation_label', [], 'id'))->toBe('Riwayat aktivitas');
    expect(trans('activitylog.table.subject_type', [], 'id'))->toBe('Tabel');
    expect(trans('activitylog.table.subject_record', [], 'id'))->toBe('Baris');
    expect(trans('activitylog.form.event', [], 'id'))->toBe('Peristiwa');
});

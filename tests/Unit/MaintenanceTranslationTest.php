<?php

uses(Tests\TestCase::class);

test('maintenance translation keys resolve in English and Indonesian', function (): void {
    expect(trans('maintenance.model_label', [], 'en'))->toBe('Maintenance');
    expect(trans('maintenance.plural_model_label', [], 'en'))->toBe('Maintenances');
    expect(trans('maintenance.statuses.in_progress', [], 'en'))->toBe('In progress');
    expect(trans('maintenance.types.preventive', [], 'en'))->toBe('Preventive');
    expect(trans('maintenance.actions.export', [], 'en'))->toBe('Export');
    expect(trans('maintenance.infolist.reported_at', [], 'en'))->toBe('Report received');

    expect(trans('maintenance.navigation_label', [], 'id'))->toBe('Pemeliharaan');
    expect(trans('maintenance.statuses.completed', [], 'id'))->toBe('Selesai');
    expect(trans('maintenance.types.repair', [], 'id'))->toBe('Perbaikan');
    expect(trans('maintenance.actions.export', [], 'id'))->toBe('Ekspor');
    expect(trans('maintenance.filters.type', [], 'id'))->toBe('Tipe');
});

<?php

uses(Tests\TestCase::class);

test('item audit translation keys resolve in English and Indonesian', function (): void {
    expect(trans('item-audit.model_label', [], 'en'))->toBe('Item audit');
    expect(trans('item-audit.plural_model_label', [], 'en'))->toBe('Item audits');
    expect(trans('item-audit.conditions.excellent', [], 'en'))->toBe('Excellent');
    expect(trans('item-audit.results.needs_maintenance', [], 'en'))->toBe('Needs maintenance');
    expect(trans('item-audit.actions.export', [], 'en'))->toBe('Export');
    expect(trans('item-audit.table.location_verified', [], 'en'))->toBe('Location matches');

    expect(trans('item-audit.navigation_label', [], 'id'))->toBe('Audit barang');
    expect(trans('item-audit.conditions.poor', [], 'id'))->toBe('Buruk');
    expect(trans('item-audit.results.dispose', [], 'id'))->toBe('Buang');
    expect(trans('item-audit.actions.export', [], 'id'))->toBe('Ekspor');
    expect(trans('item-audit.filters.condition', [], 'id'))->toBe('Kondisi');
});

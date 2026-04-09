<?php

uses(Tests\TestCase::class);

test('manufacture translation keys resolve in English and Indonesian', function (): void {
    expect(trans('manufacture.model_label', [], 'en'))->toBe('Manufacturer');
    expect(trans('manufacture.plural_model_label', [], 'en'))->toBe('Manufacturers');
    expect(trans('manufacture.navigation_label', [], 'en'))->toBe('Manufacturers');
    expect(trans('manufacture.form.warranty_lookup_url', [], 'en'))->toBe('Warranty lookup URL');
    expect(trans('manufacture.actions.import', [], 'en'))->toBe('Import');
    expect(trans('manufacture.table.name', [], 'en'))->toBe('Name');

    expect(trans('manufacture.model_label', [], 'id'))->toBe('Pabrikan');
    expect(trans('manufacture.form.support_email', [], 'id'))->toBe('Email dukungan');
    expect(trans('manufacture.actions.import', [], 'id'))->toBe('Impor');
    expect(trans('manufacture.table.deleted_at', [], 'id'))->toBe('Dihapus pada');
});

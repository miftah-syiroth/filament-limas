<?php

uses(Tests\TestCase::class);

test('unit translation keys resolve in English and Indonesian', function (): void {
    expect(trans('unit.model_label', [], 'en'))->toBe('Unit');
    expect(trans('unit.plural_model_label', [], 'en'))->toBe('Units');
    expect(trans('unit.navigation_label', [], 'en'))->toBe('Units');
    expect(trans('unit.form.name', [], 'en'))->toBe('Name');
    expect(trans('unit.infolist.created_at', [], 'en'))->toBe('Created at');
    expect(trans('unit.table.name', [], 'en'))->toBe('Name');

    expect(trans('unit.model_label', [], 'id'))->toBe('Satuan');
    expect(trans('unit.form.name', [], 'id'))->toBe('Nama');
    expect(trans('unit.table.updated_at', [], 'id'))->toBe('Diperbarui pada');
});

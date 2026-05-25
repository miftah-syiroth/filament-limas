<?php

use Tests\TestCase;

uses(TestCase::class);

test('model translation keys resolve in English and Indonesian', function (): void {
    expect(trans('model.model_label', [], 'en'))->toBe('Model');
    expect(trans('model.plural_model_label', [], 'en'))->toBe('Models');
    expect(trans('model.navigation_label', [], 'en'))->toBe('Models');
    expect(trans('model.form.min_amount_helper', [], 'en'))->toBe('Minimum quantity that must be kept in stock.');
    expect(trans('model.actions.import', [], 'en'))->toBe('Import');
    expect(trans('model.infolist.section_information', [], 'en'))->toBe('Model information');

    expect(trans('model.model_label', [], 'id'))->toBe('Model');
    expect(trans('model.form.category', [], 'id'))->toBe('Kategori');
    expect(trans('model.table.manufacturer', [], 'id'))->toBe('Pabrikan');
    expect(trans('model.table.items_quantity', [], 'en'))->toBe('Quantity in inventory');
    expect(trans('model.table.items_quantity', [], 'id'))->toBe('Jumlah stok');
    expect(trans('model.actions.import', [], 'id'))->toBe('Impor');
});

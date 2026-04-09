<?php

uses(Tests\TestCase::class);

test('category translation keys resolve in English and Indonesian', function (): void {
    expect(trans('category.model_label', [], 'en'))->toBe('Category');
    expect(trans('category.plural_model_label', [], 'en'))->toBe('Categories');
    expect(trans('category.types.consumable', [], 'en'))->toBe('Consumable');
    expect(trans('category.actions.import', [], 'en'))->toBe('Import');
    expect(trans('category.form.type', [], 'en'))->toBe('Type');

    expect(trans('category.model_label', [], 'id'))->toBe('Kategori');
    expect(trans('category.types.asset', [], 'id'))->toBe('Aset');
    expect(trans('category.types.license', [], 'id'))->toBe('Lisensi');
    expect(trans('category.actions.import', [], 'id'))->toBe('Impor');
});

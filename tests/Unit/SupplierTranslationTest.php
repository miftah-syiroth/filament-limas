<?php

uses(Tests\TestCase::class);

test('supplier translation keys resolve in English and Indonesian', function (): void {
    expect(trans('supplier.model_label', [], 'en'))->toBe('Supplier');
    expect(trans('supplier.plural_model_label', [], 'en'))->toBe('Suppliers');
    expect(trans('supplier.navigation_label', [], 'en'))->toBe('Suppliers');
    expect(trans('supplier.form.email', [], 'en'))->toBe('Email address');
    expect(trans('supplier.infolist.province', [], 'en'))->toBe('Province');
    expect(trans('supplier.table.url', [], 'en'))->toBe('Website URL');

    expect(trans('supplier.model_label', [], 'id'))->toBe('Pemasok');
    expect(trans('supplier.form.province', [], 'id'))->toBe('Provinsi');
    expect(trans('supplier.form.url', [], 'id'))->toBe('URL situs web');
    expect(trans('supplier.table.deleted_at', [], 'id'))->toBe('Dihapus pada');
});

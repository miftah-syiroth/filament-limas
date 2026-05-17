<?php

use App\Enums\ItemStatus;
use Tests\TestCase;

uses(TestCase::class);

test('item form translation keys resolve in English and Indonesian', function (): void {
    expect(trans('items.form.sections.purchase_description', [], 'en'))
        ->toBe('Purchase date and purchase price are required to calculate depreciation value.')
        ->and(trans('items.form.sections.purchase_description', [], 'id'))
        ->toBe('Tanggal pembelian dan harga pembelian perlu diisi untuk menghitung nilai depresiasi.');

    expect(trans('items.form.supplier', [], 'en'))->toBe('Supplier')
        ->and(trans('items.form.purchase_date', [], 'en'))->toBe('Purchase date')
        ->and(trans('items.form.purchase_price', [], 'en'))->toBe('Purchase price')
        ->and(trans('items.form.order_quantity', [], 'en'))->toBe('Order quantity')
        ->and(trans('items.form.location', [], 'en'))->toBe('Location')
        ->and(trans('items.form.room', [], 'en'))->toBe('Room');

    expect(trans('items.form.supplier', [], 'id'))->toBe('Pemasok')
        ->and(trans('items.form.purchase_date', [], 'id'))->toBe('Tanggal pembelian')
        ->and(trans('items.form.purchase_price', [], 'id'))->toBe('Harga pembelian')
        ->and(trans('items.form.order_quantity', [], 'id'))->toBe('Kuantitas pesanan')
        ->and(trans('items.form.room', [], 'id'))->toBe('Ruangan');
});

test('item status enum labels use translation keys', function (): void {
    expect(ItemStatus::Active->getLabel())->toBe('Active')
        ->and(ItemStatus::UnderDiagnosis->getLabel())->toBe('Under diagnosis')
        ->and(ItemStatus::Disposed->getLabel())->toBe('Disposed');

    app()->setLocale('id');

    expect(ItemStatus::Active->getLabel())->toBe('Aktif')
        ->and(ItemStatus::UnderRepair->getLabel())->toBe('Sedang diperbaiki')
        ->and(ItemStatus::Stolen->getLabel())->toBe('Dicuri');
})->after(function (): void {
    app()->setLocale('en');
});

test('all item status translation keys exist for every enum case', function (): void {
    foreach (ItemStatus::cases() as $status) {
        expect(trans('items.statuses.'.$status->value, [], 'en'))->not->toBe('items.statuses.'.$status->value)
            ->and(trans('items.statuses.'.$status->value, [], 'id'))->not->toBe('items.statuses.'.$status->value);
    }
});

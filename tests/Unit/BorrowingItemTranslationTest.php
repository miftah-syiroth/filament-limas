<?php

uses(Tests\TestCase::class);

test('borrowing item translation keys resolve in English and Indonesian', function (): void {
    expect(trans('borrowing-item.model_label', [], 'en'))->toBe('Borrowed item');
    expect(trans('borrowing-item.plural_model_label', [], 'en'))->toBe('Borrowed items');
    expect(trans('borrowing-item.table.borrower', [], 'en'))->toBe('Borrower');
    expect(trans('borrowing-item.actions.export', [], 'en'))->toBe('Export');
    expect(trans('borrowing-item.infolist.condition_out', [], 'en'))->toBe('Condition (out)');

    expect(trans('borrowing-item.navigation_label', [], 'id'))->toBe('Barang dipinjam');
    expect(trans('borrowing-item.table.borrower', [], 'id'))->toBe('Peminjam');
    expect(trans('borrowing-item.table.checked_in_at', [], 'id'))->toBe('Waktu kembali');
    expect(trans('borrowing-item.actions.export', [], 'id'))->toBe('Ekspor');
    expect(trans('borrowing-item.filters.condition_in', [], 'id'))->toBe('Kondisi masuk');
});

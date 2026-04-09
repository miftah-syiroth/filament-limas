<?php

uses(Tests\TestCase::class);

test('borrowing translation keys resolve in English and Indonesian', function (): void {
    expect(trans('borrowing.model_label', [], 'en'))->toBe('Borrowing');
    expect(trans('borrowing.statuses.returned', [], 'en'))->toBe('Returned');
    expect(trans('borrowing.form.due_at', [], 'en'))->toBe('Due date');
    expect(trans('borrowing.filters.overdue_true', [], 'en'))->toBe('Yes');
    expect(trans('borrowing.relation.add_item', [], 'en'))->toBe('Add item');

    expect(trans('borrowing.navigation_label', [], 'id'))->toBe('Peminjaman');
    expect(trans('borrowing.statuses.active', [], 'id'))->toBe('Aktif');
    expect(trans('borrowing.infolist.section_general', [], 'id'))->toBe('Informasi umum');
    expect(trans('borrowing.filters.overdue_placeholder', [], 'id'))->toBe('Semua');
    expect(trans('borrowing.relation.modal_fieldset_out', [], 'id'))->toBe('Keluar');
});

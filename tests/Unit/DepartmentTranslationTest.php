<?php

uses(Tests\TestCase::class);

test('department translation keys resolve in English and Indonesian', function (): void {
    expect(trans('department.model_label', [], 'en'))->toBe('Department');
    expect(trans('department.plural_model_label', [], 'en'))->toBe('Departments');
    expect(trans('department.navigation_label', [], 'en'))->toBe('Departments');
    expect(trans('department.form.location', [], 'en'))->toBe('Location');
    expect(trans('department.infolist.company', [], 'en'))->toBe('Company');
    expect(trans('department.table.name', [], 'en'))->toBe('Name');

    expect(trans('department.model_label', [], 'id'))->toBe('Departemen');
    expect(trans('department.form.company', [], 'id'))->toBe('Perusahaan');
    expect(trans('department.form.location', [], 'id'))->toBe('Lokasi');
    expect(trans('department.table.deleted_at', [], 'id'))->toBe('Dihapus pada');
});

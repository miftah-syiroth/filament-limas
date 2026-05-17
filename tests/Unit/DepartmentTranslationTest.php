<?php

use Tests\TestCase;

uses(TestCase::class);

test('department translation keys resolve in English and Indonesian', function (): void {
    expect(trans('department.model_label', [], 'en'))->toBe('Department');
    expect(trans('department.plural_model_label', [], 'en'))->toBe('Departments');
    expect(trans('department.navigation_label', [], 'en'))->toBe('Departments');
    expect(trans('department.form.location', [], 'en'))->toBe('Location');
    expect(trans('department.infolist.organization', [], 'en'))->toBe('Organization');
    expect(trans('department.table.name', [], 'en'))->toBe('Name');

    expect(trans('department.model_label', [], 'id'))->toBe('Departemen');
    expect(trans('department.form.organization', [], 'id'))->toBe('Organisasi');
    expect(trans('department.form.location', [], 'id'))->toBe('Lokasi');
    expect(trans('department.table.deleted_at', [], 'id'))->toBe('Dihapus pada');
});

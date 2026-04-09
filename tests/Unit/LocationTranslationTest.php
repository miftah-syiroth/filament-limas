<?php

uses(Tests\TestCase::class);

test('location translation keys resolve in English and Indonesian', function (): void {
    expect(trans('location.model_label', [], 'en'))->toBe('Location');
    expect(trans('location.plural_model_label', [], 'en'))->toBe('Locations');
    expect(trans('location.navigation_label', [], 'en'))->toBe('Locations');
    expect(trans('location.form.address', [], 'en'))->toBe('Address');
    expect(trans('location.infolist.province', [], 'en'))->toBe('Province');
    expect(trans('location.table.company', [], 'en'))->toBe('Company');

    expect(trans('location.model_label', [], 'id'))->toBe('Lokasi');
    expect(trans('location.form.company', [], 'id'))->toBe('Perusahaan');
    expect(trans('location.form.province', [], 'id'))->toBe('Provinsi');
    expect(trans('location.table.deleted_at', [], 'id'))->toBe('Dihapus pada');
});

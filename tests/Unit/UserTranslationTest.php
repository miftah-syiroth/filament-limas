<?php

uses(Tests\TestCase::class);

test('user translation keys resolve in English and Indonesian', function (): void {
    expect(trans('user.model_label', [], 'en'))->toBe('User');
    expect(trans('user.plural_model_label', [], 'en'))->toBe('Users');
    expect(trans('user.navigation_label', [], 'en'))->toBe('Users');
    expect(trans('user.form.email_verified', [], 'en'))->toBe('Email verified');
    expect(trans('user.table.email_verified', [], 'en'))->toBe('Verified');
    expect(trans('user.infolist.two_factor_confirmed_at', [], 'en'))->toBe('Two-factor confirmed at');

    expect(trans('user.navigation_label', [], 'id'))->toBe('Pengguna');
    expect(trans('user.form.password', [], 'id'))->toBe('Kata sandi');
    expect(trans('user.table.email_verified', [], 'id'))->toBe('Terverifikasi');
    expect(trans('user.infolist.email', [], 'id'))->toBe('Email');
});

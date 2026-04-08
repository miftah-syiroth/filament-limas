<?php

use App\Enums\DeprecationMethod;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

uses(TestCase::class);

test('depreciation method enum labels follow the active locale', function () {
    App::setLocale('en');

    expect(DeprecationMethod::Amount->getLabel())->toBe('Straight-line');

    App::setLocale('id');

    expect(DeprecationMethod::Amount->getLabel())->toBe('Garis lurus');
});

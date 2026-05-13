<?php

use App\Enums\DepreciationMethod;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

uses(TestCase::class);

test('depreciation method enum labels follow the active locale', function () {
    App::setLocale('en');

    expect(DepreciationMethod::Amount->getLabel())->toBe('Straight-line');

    App::setLocale('id');

    expect(DepreciationMethod::Amount->getLabel())->toBe('Garis lurus');
});

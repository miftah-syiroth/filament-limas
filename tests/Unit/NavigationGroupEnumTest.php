<?php

use App\Enums\NavigationGroup;
use Illuminate\Support\Facades\App;

test('navigation group enum labels follow the active locale', function () {
    App::setLocale('en');

    expect(NavigationGroup::Reference->getLabel())->toBe('Reference')
        ->and(NavigationGroup::Reports->getLabel())->toBe('Reports')
        ->and(NavigationGroup::Administration->getLabel())->toBe('Administration')
        ->and(NavigationGroup::MasterData->getLabel())->toBe('Master Data');

    App::setLocale('id');

    expect(NavigationGroup::Reference->getLabel())->toBe('Referensi')
        ->and(NavigationGroup::Reports->getLabel())->toBe('Laporan')
        ->and(NavigationGroup::Administration->getLabel())->toBe('Administrasi')
        ->and(NavigationGroup::MasterData->getLabel())->toBe('Data Master');
});

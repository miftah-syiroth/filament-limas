<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum NavigationGroup: string implements HasLabel
{
    case Reference = 'Reference';
    case Reports = 'Reports';
    case Administration = 'Administration';
    case MasterData = 'Master Data';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Reference => __('navigation.groups.reference'),
            self::Reports => __('navigation.groups.reports'),
            self::Administration => __('navigation.groups.administration'),
            self::MasterData => __('navigation.groups.master_data'),
        };
    }
}

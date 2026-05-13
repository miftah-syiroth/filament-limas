<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DepreciationMethod: string implements HasLabel
{
    // straight line, reducing balance, sum of the years digits
    case Amount = 'amount'; // straight line

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Amount => __('depreciation.enums.method.amount'),
        };
    }
}

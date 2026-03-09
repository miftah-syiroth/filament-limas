<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DeprecationMethod: string implements HasLabel
{
    // straight line, reducing balance, sum of the years digits
    case Amount = 'amount'; // straight line

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}

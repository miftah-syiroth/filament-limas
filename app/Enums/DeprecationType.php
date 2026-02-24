<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DeprecationType: string implements HasLabel
{
    // amount, percentage
    case Amount = 'amount';
    case Percentage = 'percentage';

    public function getLabel(): string | Htmlable | null
    {
        return $this->name;
    }
}

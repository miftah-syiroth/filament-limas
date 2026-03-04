<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum StockMovementType: string implements HasLabel
{
    case In = 'in';
    case Out = 'out';
    case Adjustment = 'adjustment';

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}

<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum BorrowingStatus: string implements HasLabel
{
    case Active = 'active';
    case Returned = 'returned';

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}

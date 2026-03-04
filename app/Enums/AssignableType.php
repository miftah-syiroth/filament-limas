<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum AssignableType: string implements HasLabel
{
    case User = 'App\Models\User';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::User => 'User',
        };
    }
}

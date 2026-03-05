<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ItemStateEventType: string implements HasLabel
{
    case Transfer = 'transfer';
    case Assignment = 'assignment';
    case StatusChange = 'status_change';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Transfer => 'Transfer',
            self::Assignment => 'Assignment',
            self::StatusChange => 'Status Change',
        };
    }
}

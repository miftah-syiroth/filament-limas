<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum RoleName: string implements HasLabel
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Operator = 'operator';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::SuperAdmin => __('filament-shield::filament-shield.field.name'),
            self::Admin => __('filament-shield::filament-shield.field.name'),
            self::Operator => __('filament-shield::filament-shield.field.name'),
            default => $this->value,
        };
    }
}

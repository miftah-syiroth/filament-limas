<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ItemAuditCondition: string implements HasLabel
{
    case Excellent = 'excellent';
    case Good = 'good';
    case Fair = 'fair';
    case Poor = 'poor';
    case Unusable = 'unusable';

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}

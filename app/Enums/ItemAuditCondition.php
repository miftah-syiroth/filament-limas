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
        return match ($this) {
            self::Excellent => __('item-audit.conditions.excellent'),
            self::Good => __('item-audit.conditions.good'),
            self::Fair => __('item-audit.conditions.fair'),
            self::Poor => __('item-audit.conditions.poor'),
            self::Unusable => __('item-audit.conditions.unusable'),
        };
    }
}

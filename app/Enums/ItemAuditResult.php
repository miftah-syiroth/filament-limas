<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ItemAuditResult: string implements HasLabel
{
    case Ok = 'ok';
    case NeedsMaintenance = 'needs_maintenance';
    case NeedsReplacement = 'needs_replacement';
    case Dispose = 'dispose';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Ok => __('item-audit.results.ok'),
            self::NeedsMaintenance => __('item-audit.results.needs_maintenance'),
            self::NeedsReplacement => __('item-audit.results.needs_replacement'),
            self::Dispose => __('item-audit.results.dispose'),
        };
    }
}

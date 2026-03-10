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
        return $this->name;
    }
}

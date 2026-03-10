<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum MaintenanceType: string implements HasLabel
{
    case Preventive = 'preventive';
    case Repair = 'repair';
    case Upgrade = 'upgrade';
    case Inspection = 'inspection';

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}

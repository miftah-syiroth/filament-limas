<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ItemStatus: string implements HasLabel
{
    case Active = 'active';
    case UnderDiagnosis = 'under_diagnosis';
    case UnderRepair = 'under_repair';
    case Damaged = 'damaged';
    case Irreparable = 'irreparable';
    case Lost = 'lost';
    case Stolen = 'stolen';
    case Archived = 'archived';
    case Disposed = 'disposed';

    /**
     * @return list<self>
     */
    public static function excludedFromInventory(): array
    {
        return [
            self::Lost,
            self::Stolen,
            self::Archived,
            self::Disposed,
        ];
    }

    public function getLabel(): string|Htmlable|null
    {
        return __('items.statuses.'.$this->value);
    }
}

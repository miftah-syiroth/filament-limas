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
    case Lost = 'lost'; // hilang
    case Stolen = 'stolen'; // dicuri
    case Archived = 'archived'; // diarsipkan
    case Disposed = 'disposed'; // dihapus dari secara fisik di real world

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}

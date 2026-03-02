<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CategoryType: string implements HasLabel
{
    // asset, accessory, consumable, license
    case Asset = 'asset';
    case Accessory = 'accessory';
    case Consumable = 'consumable';
    case License = 'license';

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}

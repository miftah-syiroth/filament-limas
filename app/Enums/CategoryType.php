<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
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

    public function getColor(): string
    {
        return match ($this) {
            self::Asset => 'primary',
            self::Accessory => 'success',
            self::Consumable => 'warning',
            self::License => 'danger',
            default => 'gray',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Asset => Heroicon::BuildingOffice,
            self::Accessory => Heroicon::ComputerDesktop,
            self::Consumable => Heroicon::ShoppingCart,
            self::License => Heroicon::Key,
        };
    }
}

<?php

namespace App\Filament\Resources\Items\Schemas\Concerns;

use App\Enums\CategoryType;
use App\Models\Category;
use Filament\Schemas\Components\Utilities\Get;

trait InteractsWithItemCategory
{
    public static function isCategoryConsumable(Get $get): bool
    {
        return Category::find($get('category_id'))?->type === CategoryType::Consumable;
    }
}

<?php

namespace App\Filament\Widgets\Stock;

use App\Enums\CategoryType;
use App\Filament\Resources\Models\ModelResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CriticalConsumableStat extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 81;

    protected function getColumns(): int
    {
        return 1;
    }

    public static function canView(): bool
    {
        return static::canViewShield('ViewAny:Model');
    }

    protected function getStats(): array
    {
        $count = static::modelsBelowMinAmount(CategoryType::Consumable, includeAtMinimum: true)->count();

        return [
            Stat::make(__('dashboard.stock.critical_consumables'), $count)
                ->color($count > 0 ? 'danger' : 'success')
                ->url(ModelResource::getUrl('index')),
        ];
    }
}

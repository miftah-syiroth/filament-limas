<?php

namespace App\Filament\Widgets\Stock;

use App\Enums\StockMovementType;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockMovementTodayStats extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 80;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('dashboard.stock.heading');
    }

    protected function getColumns(): int
    {
        return 3;
    }

    public static function canView(): bool
    {
        return static::canViewShield('ViewAny:Item');
    }

    protected function getStats(): array
    {
        $baseQuery = StockMovement::query()
            ->whereDate('created_at', today())
            ->whereHas('item', fn ($query) => $query->where('is_individual_tracking', false));

        $inCount = (clone $baseQuery)->where('type', StockMovementType::In)->count();
        $outCount = (clone $baseQuery)->where('type', StockMovementType::Out)->count();
        $adjustmentCount = (clone $baseQuery)->where('type', StockMovementType::Adjustment)->count();

        return [
            Stat::make(__('dashboard.stock.movement_in'), $inCount)->color('success'),
            Stat::make(__('dashboard.stock.movement_out'), $outCount)->color('danger'),
            Stat::make(__('dashboard.stock.movement_adjustment'), $adjustmentCount)->color('warning'),
        ];
    }
}

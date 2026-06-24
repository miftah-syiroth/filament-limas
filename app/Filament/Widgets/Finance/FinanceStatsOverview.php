<?php

namespace App\Filament\Widgets\Finance;

use App\Enums\ItemStatus;
use App\Filament\Pages\DepreciationItemsPage;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use App\Support\DepreciationCalculator;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceStatsOverview extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 90;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('dashboard.finance.heading');
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
        $purchaseValue = (float) Item::query()
            ->inInventory()
            ->whereNotNull('purchase_price')
            ->sum('purchase_price');

        $depreciationItems = Item::query()
            ->inInventory()
            ->whereNotNull('purchase_price')
            ->whereHas('model.depreciation')
            ->with(['model.depreciation'])
            ->get();

        $bookValue = DepreciationCalculator::sumDepreciatedPrice($depreciationItems, now());

        $depreciationPercent = $purchaseValue > 0
            ? round((1 - ($bookValue / $purchaseValue)) * 100, 1)
            : 0;

        $nearMinimumCount = $depreciationItems
            ->filter(function (Item $item): bool {
                $minimumValue = DepreciationCalculator::minimumValue($item);

                if ($minimumValue === null || $item->depreciated_price === null) {
                    return false;
                }

                return $item->depreciated_price <= ($minimumValue * 1.1);
            })
            ->count();

        return [
            Stat::make(__('dashboard.finance.active_purchase_value'), static::formatIdr($purchaseValue))
                ->color('primary')
                ->url(DepreciationItemsPage::getUrl()),
            Stat::make(__('dashboard.finance.active_book_value'), static::formatIdr($bookValue))
                ->description(__('dashboard.finance.depreciation_percent', ['percent' => $depreciationPercent]))
                ->color('success')
                ->url(DepreciationItemsPage::getUrl()),
            Stat::make(__('dashboard.finance.near_minimum'), $nearMinimumCount)
                ->color($nearMinimumCount > 0 ? 'warning' : 'success')
                ->url(DepreciationItemsPage::getUrl()),
        ];
    }
}

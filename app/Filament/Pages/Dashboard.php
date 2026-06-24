<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\Activity\LatestActivityLogTable;
use App\Filament\Widgets\Alert\AlertStatsOverview;
use App\Filament\Widgets\AuditMaintenance\AuditMaintenanceStatsOverview;
use App\Filament\Widgets\BarcodeScanner;
use App\Filament\Widgets\Finance\FinanceStatsOverview;
use App\Filament\Widgets\Finance\MonthlyDepreciationChart;
use App\Filament\Widgets\Inventory\AssetsApproachingEolTable;
use App\Filament\Widgets\Inventory\ExpiringWarrantyTable;
use App\Filament\Widgets\Inventory\InventoryStatsOverview;
use App\Filament\Widgets\Inventory\ItemsByCategoryChart;
use App\Filament\Widgets\Inventory\ItemsByDepartmentChart;
use App\Filament\Widgets\Inventory\ItemsByLocationChart;
use App\Filament\Widgets\Inventory\ItemsByStatusChart;
use App\Filament\Widgets\Master\MasterDataStatsOverview;
use App\Filament\Widgets\Stock\CriticalConsumableTable;
use App\Filament\Widgets\Stock\StockMovementTodayStats;
use App\Filament\Widgets\Stock\TopConsumableChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

class Dashboard extends BaseDashboard
{
    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            BarcodeScanner::class,
            AccountWidget::class,
            AlertStatsOverview::class,
            InventoryStatsOverview::class,
            ItemsByStatusChart::class,
            ItemsByCategoryChart::class,
            ItemsByLocationChart::class,
            ItemsByDepartmentChart::class,
            AssetsApproachingEolTable::class,
            ExpiringWarrantyTable::class,
            AuditMaintenanceStatsOverview::class,
            StockMovementTodayStats::class,
            CriticalConsumableTable::class,
            TopConsumableChart::class,
            FinanceStatsOverview::class,
            MonthlyDepreciationChart::class,
            MasterDataStatsOverview::class,
            LatestActivityLogTable::class,
        ];
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 2,
            'lg' => 4,
        ];
    }
}

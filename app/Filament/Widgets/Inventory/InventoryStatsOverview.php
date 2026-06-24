<?php

namespace App\Filament\Widgets\Inventory;

use App\Filament\Resources\Items\ItemResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryStatsOverview extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 20;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('dashboard.inventory.heading');
    }

    protected function getColumns(): int
    {
        return 4;
    }

    public static function canView(): bool
    {
        return static::canViewShield('ViewAny:Item');
    }

    protected function getStats(): array
    {
        $inventoryQuery = Item::query()->inInventory();

        $totalUnits = (int) (clone $inventoryQuery)->sum('quantity');
        $registeredItems = (clone $inventoryQuery)->count();

        $eolItems = Item::query()
            ->inInventory()
            ->whereNotNull('eol_date')
            ->whereBetween('eol_date', [now(), now()->addDays(90)])
            ->orderBy('eol_date')
            ->get();

        $eolCount = $eolItems->count();
        $nearestEolDays = $eolItems->first()?->eol_date?->diffInDays(now());

        $eolColor = match (true) {
            $nearestEolDays !== null && $nearestEolDays <= 30 => 'danger',
            $nearestEolDays !== null && $nearestEolDays <= 60 => 'warning',
            default => 'info',
        };

        $warrantyCount = Item::query()
            ->inInventory()
            ->whereNotNull('purchase_date')
            ->whereNotNull('warranty_months')
            ->get()
            ->filter(function (Item $item): bool {
                $warrantyEnd = $item->purchase_date->copy()->addMonths($item->warranty_months);

                return $warrantyEnd->between(now(), now()->addDays(30));
            })
            ->count();

        $itemsWithoutAuditCount = Item::query()
            ->inInventory()
            ->whereNull('last_audit_date')
            ->whereHas('model', fn ($query) => $query
                ->whereNotNull('audit_interval')
                ->where('audit_interval', '>', 0))
            ->count();

        return [
            Stat::make(__('dashboard.inventory.total_items'), number_format($totalUnits))
                ->description(
                    __('dashboard.inventory.registered_items', ['count' => number_format($registeredItems)])
                )
                ->color('primary')
                ->url(ItemResource::getUrl('index')),
            Stat::make(__('dashboard.eol.stat'), $eolCount)
                ->description(
                    $nearestEolDays !== null
                        ? __('dashboard.eol.within_days', ['days' => $nearestEolDays])
                        : null
                )
                ->color($eolColor)
                ->url(ItemResource::getUrl('index')),
            Stat::make(__('dashboard.warranty.stat'), $warrantyCount)
                ->color($warrantyCount > 0 ? 'warning' : 'success')
                ->url(ItemResource::getUrl('index')),
                Stat::make(__('dashboard.audit_maintenance.items_without_audit'), $itemsWithoutAuditCount)
                ->color($itemsWithoutAuditCount > 0 ? 'warning' : 'success')
                ->url(ItemResource::getUrl('index')),
        ];
    }
}

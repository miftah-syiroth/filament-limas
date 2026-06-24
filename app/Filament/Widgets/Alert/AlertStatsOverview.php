<?php

namespace App\Filament\Widgets\Alert;

use App\Enums\ItemStatus;
use App\Enums\MaintenanceStatus;
use App\Filament\Resources\Items\ItemResource;
use App\Filament\Resources\Maintenances\MaintenanceResource;
use App\Filament\Resources\Models\ModelResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AlertStatsOverview extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 10;

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('dashboard.alert.heading');
    }

    protected function getColumns(): int
    {
        return 5;
    }

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:Item')
    //         || static::canViewShield('ViewAny:Maintenance')
    //         || static::canViewShield('ViewAny:Model');
    // }

    protected function getStats(): array
    {
        $reportedCount = Maintenance::query()
            ->whereIn('status', [MaintenanceStatus::Reported, MaintenanceStatus::InProgress])
            ->count();

        $inProgressCount = Maintenance::query()
            ->where('status', MaintenanceStatus::InProgress)
            ->count();

        $openMaintenanceCount = $reportedCount + $inProgressCount;

        $overdueAuditCount = Item::query()
            ->inInventory()
            ->whereNotNull('next_audit_date')
            ->where('next_audit_date', '<', now())
            ->count();

        $upcomingAuditCount = Item::query()
            ->inInventory()
            ->whereNotNull('next_audit_date')
            ->whereBetween('next_audit_date', [now(), now()->addDays(7)])
            ->count();

        $auditDueCount = $overdueAuditCount + $upcomingAuditCount;

        $belowMinStockCount = static::modelsBelowMinAmount()->count();

        $problemItemsCount = Item::query()
            ->whereIn('status', [
                ItemStatus::Damaged,
                ItemStatus::UnderRepair,
                ItemStatus::UnderDiagnosis,
                ItemStatus::Irreparable,
            ])
            ->count();

        $lostCount = Item::query()->where('status', ItemStatus::Lost)->count();
        $stolenCount = Item::query()->where('status', ItemStatus::Stolen)->count();

        return [
            Stat::make(__('dashboard.alert.open_maintenance'), $openMaintenanceCount)
                ->description(
                    __('dashboard.alert.maintenance_reported', ['count' => $reportedCount])
                    .' · '
                    .__('dashboard.alert.maintenance_in_progress', ['count' => $inProgressCount])
                )
                ->color('warning')
                ->url(MaintenanceResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'values' => [
                                MaintenanceStatus::Reported->value,
                                MaintenanceStatus::InProgress->value,
                            ],
                        ],
                    ],
                ])),
            Stat::make(__('dashboard.alert.audit_due'), $auditDueCount)
                ->description(
                    __('dashboard.alert.audit_overdue', ['count' => $overdueAuditCount])
                    .' · '
                    .__('dashboard.alert.audit_upcoming', ['count' => $upcomingAuditCount])
                )
                ->color($overdueAuditCount > 0 ? 'danger' : 'warning')
                ->url(ItemResource::getUrl('index')),
            Stat::make(__('dashboard.alert.below_min_stock'), $belowMinStockCount)
                ->color('danger')
                ->url(ModelResource::getUrl('index')),
            Stat::make(__('dashboard.alert.problem_items'), $problemItemsCount)
                ->color('warning')
                ->url(ItemResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'values' => [
                                ItemStatus::Damaged->value,
                                ItemStatus::UnderRepair->value,
                                ItemStatus::UnderDiagnosis->value,
                                ItemStatus::Irreparable->value,
                            ],
                        ],
                    ],
                ])),
            Stat::make(__('dashboard.alert.lost_stolen'), $lostCount + $stolenCount)
                ->description(
                    __('dashboard.alert.lost_count', ['count' => $lostCount])
                    .' · '
                    .__('dashboard.alert.stolen_count', ['count' => $stolenCount])
                )
                ->color('danger')
                ->url(ItemResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'values' => [
                                ItemStatus::Lost->value,
                                ItemStatus::Stolen->value,
                            ],
                        ],
                    ],
                ])),
        ];
    }
}

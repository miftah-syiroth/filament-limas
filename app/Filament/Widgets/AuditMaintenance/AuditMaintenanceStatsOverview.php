<?php

namespace App\Filament\Widgets\AuditMaintenance;

use App\Enums\ItemAuditResult;
use App\Enums\MaintenanceStatus;
use App\Filament\Resources\ItemAudits\ItemAuditResource;
use App\Filament\Resources\Items\ItemResource;
use App\Filament\Resources\Maintenances\MaintenanceResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use App\Models\ItemAudit;
use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuditMaintenanceStatsOverview extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 60;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('dashboard.audit_maintenance.heading');
    }

    protected function getColumns(): int
    {
        return 4;
    }

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:ItemAudit')
    //         || static::canViewShield('ViewAny:Maintenance')
    //         || static::canViewShield('ViewAny:Item');
    // }

    protected function getStats(): array
    {
        $auditsThisMonth = ItemAudit::query()
            ->whereBetween('audited_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $auditsLastMonth = ItemAudit::query()
            ->whereBetween('audited_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        $deltaPercent = $auditsLastMonth > 0
            ? round((($auditsThisMonth - $auditsLastMonth) / $auditsLastMonth) * 100, 1)
            : ($auditsThisMonth > 0 ? 100 : 0);

        $problemResultsCount = ItemAudit::query()
            ->whereIn('result', [
                ItemAuditResult::NeedsMaintenance,
                ItemAuditResult::NeedsReplacement,
                ItemAuditResult::Dispose,
            ])
            ->where('audited_at', '>=', now()->subDays(30))
            ->count();

        $unverifiedLocationCount = ItemAudit::query()
            ->where('location_verified', false)
            ->count();

        $maintenanceCost = (float) Maintenance::query()
            ->where('status', MaintenanceStatus::Completed)
            ->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereNotNull('cost')
            ->sum('cost');



        return [
            Stat::make(__('dashboard.audit_maintenance.audits_this_month'), $auditsThisMonth)
                ->description(__('dashboard.audit_maintenance.vs_last_month', ['delta' => $deltaPercent]))
                ->color('primary')
                ->url(ItemAuditResource::getUrl('index')),
            Stat::make(__('dashboard.audit_maintenance.problem_results'), $problemResultsCount)
                ->color('warning')
                ->url(ItemAuditResource::getUrl('index')),
            Stat::make(__('dashboard.audit_maintenance.unverified_location'), $unverifiedLocationCount)
                ->color('danger')
                ->url(ItemAuditResource::getUrl('index')),
            Stat::make(__('dashboard.audit_maintenance.maintenance_cost'), static::formatIdr($maintenanceCost))
                ->color('success')
                ->url(MaintenanceResource::getUrl('index')),

        ];
    }
}

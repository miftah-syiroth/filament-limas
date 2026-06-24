<?php

namespace App\Filament\Widgets\AuditMaintenance;

use App\Enums\MaintenanceType;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Maintenance;
use Filament\Widgets\ChartWidget;

class MaintenanceByTypeChart extends ChartWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 70;

    public function getHeading(): ?string
    {
        return __('dashboard.maintenance_by_type');
    }

    protected ?string $maxHeight = '300px';

    public static function canView(): bool
    {
        return static::canViewShield('ViewAny:Maintenance');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $counts = Maintenance::query()
            ->where('reported_at', '>=', now()->subMonths(12))
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $labels = [];
        $data = [];
        $colors = ['#3b82f6', '#22c55e', '#f59e0b', '#8b5cf6'];

        foreach ($counts as $type => $total) {
            $labels[] = MaintenanceType::from($type)->getLabel();
            $data[] = (int) $total;
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }
}

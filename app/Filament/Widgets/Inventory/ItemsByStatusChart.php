<?php

namespace App\Filament\Widgets\Inventory;

use App\Enums\ItemStatus;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use Filament\Widgets\ChartWidget;

class ItemsByStatusChart extends ChartWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 30;

    protected ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('dashboard.items_by_status');
    }

    protected ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = [
        'lg' => 2,
    ];

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:Item');
    // }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $counts = Item::query()
            ->inInventory()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [];
        $data = [];
        $colors = [];

        $colorMap = [
            ItemStatus::Active->value => '#22c55e',
            ItemStatus::UnderDiagnosis->value => '#f59e0b',
            ItemStatus::UnderRepair->value => '#f97316',
            ItemStatus::Damaged->value => '#ef4444',
            ItemStatus::Irreparable->value => '#b91c1c',
        ];

        foreach ($counts as $status => $total) {
            $enum = ItemStatus::from($status);
            $labels[] = $enum->getLabel();
            $data[] = (int) $total;
            $colors[] = $colorMap[$status] ?? '#94a3b8';
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }
}

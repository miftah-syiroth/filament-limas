<?php

namespace App\Filament\Widgets\Inventory;

use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use Filament\Widgets\ChartWidget;

class ItemsByDepartmentChart extends ChartWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 41;

    public function getHeading(): ?string
    {
        return __('dashboard.items_by_department');
    }

    protected int|string|array $columnSpan = [
        'lg' => 2,
    ];

    protected ?string $maxHeight = '320px';

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:Item');
    // }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function getOptions(): ?array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $grouped = Item::query()
            ->inInventory()
            ->with('department')
            ->get()
            ->groupBy(fn (Item $item): string => $item->department?->name ?? __('dashboard.no_department'));

        $sorted = $grouped
            ->map(fn ($items) => $items->count())
            ->sortDesc()
            ->take(10);

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.items_by_department'),
                    'data' => $sorted->values()->all(),
                    'backgroundColor' => '#8b5cf6',
                ],
            ],
            'labels' => $sorted->keys()->all(),
        ];
    }
}

<?php

namespace App\Filament\Widgets\Inventory;

use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use Filament\Widgets\ChartWidget;

class ItemsByLocationChart extends ChartWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 40;

    public function getHeading(): ?string
    {
        return __('dashboard.items_by_location');
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
            ->with('location')
            ->get()
            ->groupBy(fn (Item $item): string => $item->location?->name ?? __('dashboard.no_location'));

        $sorted = $grouped
            ->map(fn ($items) => $items->count())
            ->sortDesc()
            ->take(10);

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.items_by_location'),
                    'data' => $sorted->values()->all(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $sorted->keys()->all(),
        ];
    }
}

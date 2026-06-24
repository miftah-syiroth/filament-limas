<?php

namespace App\Filament\Widgets\Inventory;

use App\Enums\CategoryType;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ItemsByCategoryChart extends ChartWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 31;

    public function getHeading(): ?string
    {
        return __('dashboard.items_by_category');
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
        return count($this->getCategoryLabels()) > 6 ? 'bar' : 'doughnut';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $rows = Item::query()
            ->inInventory()
            ->join('models', 'items.model_id', '=', 'models.id')
            ->join('categories', 'models.category_id', '=', 'categories.id')
            ->select('categories.type', DB::raw('COUNT(items.id) as total'))
            ->groupBy('categories.type')
            ->orderByDesc('total')
            ->get();

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($rows as $row) {
            $type = CategoryType::from($row->type);
            $labels[] = $type->getLabel();
            $data[] = (int) $row->total;
            $colors[] = match ($type) {
                CategoryType::Asset => '#f59e0b',
                CategoryType::Accessory => '#22c55e',
                CategoryType::Consumable => '#f97316',
                CategoryType::License => '#ef4444',
            };
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

    /**
     * @return list<string>
     */
    protected function getCategoryLabels(): array
    {
        return Item::query()
            ->inInventory()
            ->join('models', 'items.model_id', '=', 'models.id')
            ->join('categories', 'models.category_id', '=', 'categories.id')
            ->distinct()
            ->pluck('categories.type')
            ->all();
    }
}

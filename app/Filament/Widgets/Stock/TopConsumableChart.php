<?php

namespace App\Filament\Widgets\Stock;

use App\Enums\CategoryType;
use App\Enums\StockMovementType;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Model;
use App\Models\StockMovement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopConsumableChart extends ChartWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 83;

    protected int|string|array $columnSpan = [
        'lg' => 2,
    ];

    public function getHeading(): ?string
    {
        return __('dashboard.top_consumables');
    }

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
        $rows = StockMovement::query()
            ->join('items', 'stock_movements.item_id', '=', 'items.id')
            ->join('models', 'items.model_id', '=', 'models.id')
            ->join('categories', 'models.category_id', '=', 'categories.id')
            ->where('stock_movements.type', StockMovementType::Out)
            ->where('stock_movements.created_at', '>=', now()->subDays(30))
            ->where('categories.type', CategoryType::Consumable)
            ->select('items.model_id', DB::raw('SUM(ABS(stock_movements.quantity)) as total_out'))
            ->groupBy('items.model_id')
            ->orderByDesc(DB::raw('SUM(ABS(stock_movements.quantity))'))
            ->limit(10)
            ->get();

        $modelNames = Model::query()
            ->whereIn('id', $rows->pluck('model_id'))
            ->pluck('name', 'id');

        $labels = $rows->map(fn ($row) => $modelNames[$row->model_id] ?? '-')->all();
        $data = $rows->pluck('total_out')->map(fn ($value) => (int) $value)->all();

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.top_consumables'),
                    'data' => $data,
                    'backgroundColor' => '#f97316',
                ],
            ],
            'labels' => $labels,
        ];
    }
}

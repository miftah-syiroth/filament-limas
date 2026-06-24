<?php

namespace App\Filament\Widgets\Finance;

use App\Enums\ItemStatus;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use App\Support\DepreciationCalculator;
use Filament\Widgets\ChartWidget;

class MonthlyDepreciationChart extends ChartWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 100;

    public function getHeading(): ?string
    {
        return __('dashboard.finance.monthly_depreciation');
    }

    protected ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = 'full';

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:Item');
    // }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $items = Item::query()
            ->where('status', ItemStatus::Active)
            ->whereNotNull('purchase_price')
            ->whereNotNull('purchase_date')
            ->whereHas('model.depreciation')
            ->with(['model.depreciation'])
            ->get();

        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $depreciation = 0.0;

            foreach ($items as $item) {
                $startValue = DepreciationCalculator::depreciatedPriceAt($item, $start);
                $endValue = DepreciationCalculator::depreciatedPriceAt($item, $end);

                if ($startValue !== null && $endValue !== null) {
                    $depreciation += max(0, $startValue - $endValue);
                }
            }

            $labels[] = $month->translatedFormat('M Y');
            $data[] = round($depreciation, 0);
        }

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.finance.monthly_depreciation'),
                    'data' => $data,
                    'backgroundColor' => '#6366f1',
                ],
            ],
            'labels' => $labels,
        ];
    }
}

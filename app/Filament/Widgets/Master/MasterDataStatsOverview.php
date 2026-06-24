<?php

namespace App\Filament\Widgets\Master;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Manufactures\ManufactureResource;
use App\Filament\Resources\Models\ModelResource;
use App\Filament\Resources\Suppliers\SupplierResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Category;
use App\Models\Manufacture;
use App\Models\Model;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MasterDataStatsOverview extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 110;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    public static function canView(): bool
    {
        return static::canViewShield('ViewAny:Model')
            || static::canViewShield('ViewAny:Category')
            || static::canViewShield('ViewAny:Supplier')
            || static::canViewShield('ViewAny:Manufacture');
    }

    public function getHeading(): ?string
    {
        return __('dashboard.master.models_categories');
    }

    protected function getStats(): array
    {
        return [
            Stat::make(__('dashboard.master.models'), Model::query()->count())
                ->color('primary')
                ->url(ModelResource::getUrl('index')),
            Stat::make(__('dashboard.master.categories'), Category::query()->count())
                ->color('info')
                ->url(CategoryResource::getUrl('index')),
            Stat::make(__('dashboard.master.suppliers'), Supplier::query()->count())
                ->color('success')
                ->url(SupplierResource::getUrl('index')),
            Stat::make(__('dashboard.master.manufactures'), Manufacture::query()->count())
                ->color('warning')
                ->url(ManufactureResource::getUrl('index')),
        ];
    }
}

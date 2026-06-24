<?php

namespace App\Filament\Widgets\Concerns;

use App\Enums\CategoryType;
use App\Models\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;

trait InteractsWithDashboard
{
    protected static function canViewShield(string $permission): bool
    {
        /** @var Authenticatable|null $user */
        $user = auth()->user();

        return $user !== null && $user->can($permission);
    }

    protected static function formatIdr(float|int|null $amount): string
    {
        if ($amount === null) {
            return '-';
        }

        return Number::currency($amount, 'IDR', 'id', precision: 0);
    }

    /**
     * @return Collection<int, Model>
     */
    protected static function modelsBelowMinAmount(?CategoryType $categoryType = null, bool $includeAtMinimum = false): Collection
    {
        $query = Model::query()->whereNotNull('min_amount');

        if ($categoryType !== null) {
            $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('type', $categoryType));
        }

        return $query
            ->withSum('itemsInInventory as total_quantity', 'quantity')
            ->get()
            ->filter(function (Model $model) use ($includeAtMinimum): bool {
                $total = (int) ($model->total_quantity ?? 0);

                return $includeAtMinimum
                    ? $total <= $model->min_amount
                    : $total < $model->min_amount;
            })
            ->values();
    }
}

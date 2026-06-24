<?php

namespace App\Support;

use App\Models\Item;
use Carbon\CarbonInterface;

class DepreciationCalculator
{
    public static function depreciatedPriceAt(Item $item, CarbonInterface $asOf): ?float
    {
        if ($item->purchase_price === null || $item->purchase_date === null) {
            return null;
        }

        $depreciation = $item->model?->depreciation;

        if ($depreciation === null) {
            return null;
        }

        $months = (int) $depreciation->months;

        if ($months <= 0) {
            return (float) $item->purchase_price;
        }

        $minimumPercent = (float) $depreciation->minimum_value;
        $minimumValue = $item->purchase_price * ($minimumPercent / 100);

        $monthsPassed = max(
            0,
            $item->purchase_date->diffInMonths($asOf),
        );

        if ($monthsPassed >= $months) {
            return round($minimumValue, 2);
        }

        $monthlyDepreciation = ($item->purchase_price - $minimumValue) / $months;

        $depreciatedPrice = $item->purchase_price - ($monthlyDepreciation * $monthsPassed);

        return round(max($minimumValue, $depreciatedPrice), 0);
    }

    public static function minimumValue(Item $item): ?float
    {
        if ($item->purchase_price === null || $item->model?->depreciation === null) {
            return null;
        }

        return round(
            $item->purchase_price * ($item->model->depreciation->minimum_value / 100),
            0,
        );
    }

    /**
     * @param  iterable<int, Item>  $items
     */
    public static function sumDepreciatedPrice(iterable $items, CarbonInterface $asOf): float
    {
        $sum = 0.0;

        foreach ($items as $item) {
            $price = self::depreciatedPriceAt($item, $asOf);

            if ($price !== null) {
                $sum += $price;
            }
        }

        return $sum;
    }
}

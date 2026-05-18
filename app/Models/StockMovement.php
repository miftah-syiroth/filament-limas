<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StockMovement extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'item_id',
        'type',
        'quantity',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    protected static function booted(): void
    {
        static::creating(function (StockMovement $stockMovement): void {
            static::assertStockMovementDoesNotCauseNegativeBalance($stockMovement);
        });

        static::updating(function (StockMovement $stockMovement): void {
            static::assertStockMovementDoesNotCauseNegativeBalance($stockMovement);
        });

        static::created(fn (StockMovement $stockMovement) => $stockMovement->recalculateItemQuantity());
        static::updated(fn (StockMovement $stockMovement) => $stockMovement->recalculateItemQuantity());
        static::deleted(fn (StockMovement $stockMovement) => $stockMovement->recalculateItemQuantity());
    }

    protected static function assertStockMovementDoesNotCauseNegativeBalance(StockMovement $stockMovement): void
    {
        $item = $stockMovement->item ?? Item::query()->find($stockMovement->item_id);

        if ($item === null) {
            return;
        }

        $balance = (int) $item->stockMovements()
            ->when(
                $stockMovement->exists,
                fn ($query) => $query->whereKeyNot($stockMovement->getKey()),
            )
            ->sum('quantity');

        if ($balance + (int) $stockMovement->quantity < 0) {
            throw ValidationException::withMessages([
                'quantity' => __('items.pages.stock_movements.validation.would_cause_negative_stock', [
                    'balance' => $balance,
                ]),
            ]);
        }
    }

    protected function recalculateItemQuantity(): void
    {
        $item = $this->item;

        if (! $item || $item->is_individual_tracking) {
            return;
        }

        $item->updateQuietly([
            'quantity' => $item->stockMovements()->sum('quantity'),
        ]);
    }
}

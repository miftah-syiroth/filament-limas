<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use HasUuids, SoftDeletes;

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

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    protected static function booted(): void
    {
        static::created(fn (StockMovement $stockMovement) => $stockMovement->recalculateItemQuantity());
        static::updated(fn (StockMovement $stockMovement) => $stockMovement->recalculateItemQuantity());
        static::deleted(fn (StockMovement $stockMovement) => $stockMovement->recalculateItemQuantity());
        static::restored(fn (StockMovement $stockMovement) => $stockMovement->recalculateItemQuantity());
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

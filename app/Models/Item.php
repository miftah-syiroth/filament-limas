<?php

namespace App\Models;

use App\Enums\AssignableType;
use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends BaseModel
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'model_id',
        'location_id',
        'department_id',
        'supplier_id',
        'assignable_id',
        'assignable_type',
        'unit_id',
        'name',
        'serial_number',
        'quantity',
        'order_quantity',
        'purchase_date',
        'purchase_price',
        'eol_date',
        'warranty_months',
        'is_individual_tracking',
        'status',
        'notes',
        'status_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'datetime',
            'purchase_price' => 'decimal:2',
            'eol_date' => 'datetime',
            'status_updated_at' => 'datetime',
            'is_individual_tracking' => 'boolean',
            'status' => ItemStatus::class,
            'assignable_type' => AssignableType::class,
        ];
    }

    // relationships
    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::saving(function (Item $item): void {
            if ($item->model?->category?->type === CategoryType::Consumable && $item->is_individual_tracking) {
                $item->is_individual_tracking = false;
            }
        });
        static::updated(function (Item $item): void {
            // if ($item->wasChanged('unit_id')) {
            //     $item->stockMovements()->update(['unit_name' => $item->unit_name]);
            // }
        });
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'item_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}

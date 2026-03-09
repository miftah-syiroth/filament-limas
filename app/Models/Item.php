<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends BaseModel
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'model_id',
        'location_id',
        'department_id',
        'supplier_id',
        'user_id',
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
        'last_audit_date',
        'next_audit_date',
    ];

    // appends deprecated_price
    public function getDeprecatedPriceAttribute(): ?float
    {
        if ($this->purchase_price === null || $this->purchase_date === null) {
            return null;
        }

        if ($this->model->deprecation === null) {
            return null;
        }

        if ($this->purchase_date->isFuture()) {
            return (float) $this->purchase_price;
        }

        $minimum_percent = (float) $this->model->deprecation->minimum_value;
        $deprecation_months = $this->model->deprecation->months;

        $minimum_value = $this->purchase_price * ($minimum_percent / 100);
        $months_passed = max(0, $this->purchase_date->diffInMonths(now()));

        if ($months_passed >= $deprecation_months) {
            return round($minimum_value, 2);
        }

        $monthly_depreciation = ($this->purchase_price - $minimum_value) / $deprecation_months;
        $deprecated_price = $this->purchase_price - ($monthly_depreciation * $months_passed);

        return max($minimum_value, round($deprecated_price, 2));
    }

    protected function casts(): array
    {
        return [
            'purchase_date' => 'datetime',
            'purchase_price' => 'decimal:2',
            'eol_date' => 'datetime',
            'status_updated_at' => 'datetime',
            'is_individual_tracking' => 'boolean',
            'status' => ItemStatus::class,
            'last_audit_date' => 'datetime',
            'next_audit_date' => 'datetime',
        ];
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'item_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stateLogs(): HasMany
    {
        return $this->hasMany(ItemStateLog::class, 'item_id');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(ItemAudit::class, 'item_id');
    }
}

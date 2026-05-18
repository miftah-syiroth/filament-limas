<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Item extends BaseModel implements HasMedia
{
    use HasUuids, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'model_id',
        'serial_number',
        'location_id',
        'department_id',
        'room_id',
        'supplier_id',
        'user_id',
        'name',
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

        //
        'last_audit_date',
        'next_audit_date',
    ];

    protected $appends = ['borrowable_quantity'];

    protected $with = ['activeBorrowingItems'];

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['status_updated_at', 'notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function borrowableQuantity(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $borrowed = $this->activeBorrowingItems->sum('quantity');

                return max(0, $this->quantity - $borrowed);
            }
        );
    }

    protected function depreciatedPrice(): Attribute
    {
        return Attribute::get(function (): ?float {

            if ($this->purchase_price === null || $this->purchase_date === null) {
                return null;
            }

            $depreciation = $this->model?->depreciation;

            if ($depreciation === null) {
                return null;
            }

            $months = (int) $depreciation->months;

            if ($months <= 0) {
                return (float) $this->purchase_price;
            }

            $minimumPercent = (float) $depreciation->minimum_value;

            $minimumValue = $this->purchase_price * ($minimumPercent / 100);

            $monthsPassed = max(
                0,
                $this->purchase_date->diffInMonths(now())
            );

            if ($monthsPassed >= $months) {
                return round($minimumValue, 2);
            }

            $monthlyDepreciation =
                ($this->purchase_price - $minimumValue) / $months;

            $depreciatedPrice =
                $this->purchase_price -
                ($monthlyDepreciation * $monthsPassed);

            return round(max($minimumValue, $depreciatedPrice), 2);
        });
    }

    protected static function booted(): void
    {
        static::saving(function (Item $item): void {
            if ($item->model?->category?->type === CategoryType::Consumable && $item->is_individual_tracking) {
                $item->is_individual_tracking = false;
            }
        });
    }

    #[Scope]
    public function scopeBorrowable(Builder $query): void
    {
        $query->where('status', ItemStatus::Active)
            ->whereRaw('
                quantity > COALESCE(
                    (
                        SELECT SUM(borrowing_items.quantity)
                        FROM borrowing_items
                        WHERE borrowing_items.item_id = items.id
                        AND borrowing_items.checked_in_at IS NULL
                    ), 0
                )
            ');
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

    public function stateLogs(): HasMany
    {
        return $this->hasMany(ItemStateLog::class, 'item_id');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(ItemAudit::class, 'item_id');
    }

    public function latestAudit(): HasOne
    {
        return $this->hasOne(ItemAudit::class)->latest('audited_at');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'item_id');
    }

    public function latestMaintenance(): HasOne
    {
        return $this->hasOne(Maintenance::class)->latest('reported_at');
    }

    public function borrowingItems(): HasMany
    {
        return $this->hasMany(BorrowingItem::class, 'item_id');
    }

    public function activeBorrowingItems(): HasMany
    {
        return $this->hasMany(BorrowingItem::class, 'item_id')
            ->whereNull('checked_in_at');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}

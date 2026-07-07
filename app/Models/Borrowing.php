<?php

namespace App\Models;

use App\Enums\BorrowingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Borrowing extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'user_id',
        'to_location_id',
        'to_department_id',
        'to_room_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'status' => BorrowingStatus::class,
    ];

    protected $attributes = [
        'status' => BorrowingStatus::Active,
    ];

    // append
    protected $appends = ['overdue'];

    protected function overdue(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                if ($this->returned_at === null) {
                    // Overdue if not returned and due date has passed (strictly before today)
                    return $this->due_at !== null && $this->due_at < now()->startOfDay();
                }

                // Overdue if returned after due date
                return $this->due_at !== null && $this->returned_at > $this->due_at;
            },
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function toRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'to_room_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BorrowingItem::class, 'borrowing_id');
    }

    public function markReturnedIfAllItemsCheckedIn(): void
    {
        if (! $this->items()->exists()) {
            return;
        }

        if ($this->items()->whereNull('checked_in_at')->exists()) {
            return;
        }

        if ($this->status === BorrowingStatus::Returned && $this->returned_at !== null) {
            return;
        }

        $this->update([
            'status' => BorrowingStatus::Returned,
            'returned_at' => $this->returned_at ?? now(),
        ]);
    }
}

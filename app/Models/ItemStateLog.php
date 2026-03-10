<?php

namespace App\Models;

use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemStateLog extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'item_id',
        'item_audit_id',
        'event_type',
        'from_location_id',
        'to_location_id',
        'from_department_id',
        'to_department_id',
        'from_user_id',
        'to_user_id',
        'from_status',
        'to_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'event_type' => ItemStateEventType::class,
            'from_status' => ItemStatus::class,
            'to_status' => ItemStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::created(function (ItemStateLog $stateLog): void {
            $stateLog->syncItem();
        });
    }

    public function syncItem(): void
    {
        $item = $this->item;
        if ($item === null) {
            return;
        }

        $updates = [];

        if ($this->event_type === ItemStateEventType::Transfer) {
            if ($this->to_location_id !== null) {
                $updates['location_id'] = $this->to_location_id;
            }
            if ($this->to_department_id !== null) {
                $updates['department_id'] = $this->to_department_id;
            }
        }

        if ($this->event_type === ItemStateEventType::Assignment && $this->to_user_id !== null) {
            $updates['user_id'] = $this->to_user_id;
        }

        if ($this->event_type === ItemStateEventType::StatusChange && $this->to_status !== null) {
            $updates['status'] = $this->to_status;
            $updates['status_updated_at'] = now();
        }

        if ($updates !== []) {
            $item->update($updates);
        }
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function itemAudit(): BelongsTo
    {
        return $this->belongsTo(ItemAudit::class, 'item_audit_id');
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}

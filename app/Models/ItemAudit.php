<?php

namespace App\Models;

use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemAudit extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'item_id',
        'status',
        'location_verified',
        'notes',
        'audited_at',
        'next_audit_at'
    ];

    protected $casts = [
        'status' => ItemStatus::class,
        'location_verified' => 'boolean',
        'audited_at' => 'datetime',
        'next_audit_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (ItemAudit $audit): void {
            // update item last audit date and next audit date
            $audit->item->update([
                'last_audit_date' => $audit->audited_at,
                'next_audit_date' => $audit->audited_at->addMonths($audit->item->model->audit_interval),
            ]);

            ItemStateLog::create([
                'item_id' => $audit->item_id,
                'event_type' => ItemStateEventType::StatusChange,
                'from_status' => $audit->item->status,
                'to_status' => $audit->status,
                'notes' => $audit->notes,
            ]);
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}

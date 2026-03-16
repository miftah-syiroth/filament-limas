<?php

namespace App\Models;

use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ItemAudit extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $fillable = [
        'item_id',
        'location_verified',
        'audited_at',
        'next_audit_at',
        'condition',
        'result',
        'notes',
    ];

    protected $casts = [
        'condition' => ItemAuditCondition::class,
        'result' => ItemAuditResult::class,
        'location_verified' => 'boolean',
        'audited_at' => 'datetime',
        'next_audit_at' => 'datetime',
    ];

    public function getCodeAttribute(): string
    {
        return substr($this->id, -8);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted(): void
    {
        static::created(function (ItemAudit $audit): void {
            $audit->item->update([
                'last_audit_date' => $audit->audited_at,
                'next_audit_date' => $audit->audited_at->addMonths($audit->item->model->audit_interval),
            ]);
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}

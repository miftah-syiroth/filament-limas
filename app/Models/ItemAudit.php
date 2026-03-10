<?php

namespace App\Models;

use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemAudit extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'item_id',
        'location_verified',
        'notes',
        'audited_at',
        'next_audit_at',
        'condition',
        'result',
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
        $parts = explode('-', $this->id);
        $uniquePart = end($parts);

        return $uniquePart;
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

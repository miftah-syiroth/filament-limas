<?php

namespace App\Models;

use App\Enums\ItemAuditCondition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BorrowingItem extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'borrowing_id',
        'item_id',
        'quantity',
        'checked_out_at',
        'condition_out',
        'checked_in_at',
        'condition_in',
        'notes',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'condition_in' => ItemAuditCondition::class,
        'condition_out' => ItemAuditCondition::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

<?php

namespace App\Models;

use App\Enums\ItemAuditCondition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowingItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'borrowing_id',
        'item_id',
        'quantity',
        'checked_out_at',
        'checked_in_at',
        'condition_out',
        'condition_in',
        'notes',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'condition_in' => ItemAuditCondition::class,
        'condition_out' => ItemAuditCondition::class,
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

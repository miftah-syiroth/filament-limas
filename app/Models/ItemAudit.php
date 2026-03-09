<?php

namespace App\Models;

use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemAudit extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'item_id',
        'status',
        'location_verified',
        'notes',
        'audited_at',
    ];

    protected $casts = [
        'status' => ItemStatus::class,
        'location_verified' => 'boolean',
        'audited_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}

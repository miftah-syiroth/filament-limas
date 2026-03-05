<?php

namespace App\Models;

use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemStateLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'item_id',
        'event_type',
        'from_location_id',
        'to_location_id',
        'from_department_id',
        'to_department_id',
        'from_assignable_type',
        'from_assignable_id',
        'to_assignable_type',
        'to_assignable_id',
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

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

<?php

namespace App\Models;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'item_id',
        'type',
        'reported_at',
        'started_at',
        'completed_at',
        'item_audit_id',
        'status',
        'cost',
        'notes',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
        'status' => MaintenanceStatus::class,
        'type' => MaintenanceType::class,
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function itemAudit(): BelongsTo
    {
        return $this->belongsTo(ItemAudit::class, 'item_audit_id');
    }

    public function stateLogs(): HasMany
    {
        return $this->hasMany(ItemStateLog::class, 'maintenance_id');
    }
}

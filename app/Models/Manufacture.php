<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Manufacture extends EloquentModel
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'support_url',
        'support_phone',
        'support_email',
        'warranty_lookup_url',
        'notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function models(): HasMany
    {
        return $this->hasMany(Model::class);
    }
}

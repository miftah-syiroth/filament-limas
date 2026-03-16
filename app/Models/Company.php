<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Company extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Location extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'name',
        'organization_id',
        'address',
        'address2',
        'city',
        'province',
        'country',
        'zip',
        'phone',
        'notes',
        // manager_id. belum diimplementasikan
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes', 'address2'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function relationCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    public function relationProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province', 'code');
    }

    public function relationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'code');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}

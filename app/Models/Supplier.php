<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Supplier extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'name',
        'address',
        'address2',
        'city',
        'province',
        'country',
        'zip',
        'phone',
        'email',
        'url',
        'notes',
    ];

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

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes', 'address2'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

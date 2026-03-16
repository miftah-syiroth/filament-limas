<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Supplier extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'address',
        'address2',
        'city',
        'state',
        'country',
        'zip',
        'phone',
        'email',
        'url',
        'notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes', 'address2'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

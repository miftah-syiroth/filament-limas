<?php

namespace App\Models;

use App\Enums\DeprecationMethod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Deprecation extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'name',
        'months',
        'minimum_value', // percentase penyusutan maksimal
        'method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'minimum_value' => 'decimal:2',
            'method' => DeprecationMethod::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

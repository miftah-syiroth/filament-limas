<?php

namespace App\Models;

use App\Enums\DepreciationMethod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Depreciation extends Model
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
            'method' => DepreciationMethod::class,
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

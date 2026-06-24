<?php

namespace App\Models;

use App\Enums\DepreciationMethod;
use App\Models\Model as ModelsModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Depreciation extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'name',
        'months',
        'minimum_value', // PERCENTAGE penyusutan maksimal
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

    // hasMany models
    public function models(): HasMany
    {
        return $this->hasMany(ModelsModel::class);
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

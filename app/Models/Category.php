<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Models\Model as ModelCategory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => CategoryType::class,
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

    public function models(): HasMany {
        return $this->hasMany(ModelCategory::class);
    }
}

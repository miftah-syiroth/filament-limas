<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Model extends EloquentModel implements HasMedia
{
    use HasUuids, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'model_number',
        'min_amount', // jumlah minimal yang harus ada di inventory untuk pembatasan stock, peminjaman dan alert
        'end_of_life', // int in months
        'manufacture_id',
        'category_id',
        'depreciation_id',
        'audit_interval', // in months
        'notes',
        'unit_id',
    ];

    protected $casts = [
        'audit_interval' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacture()
    {
        return $this->belongsTo(Manufacture::class);
    }

    public function depreciation()
    {
        return $this->belongsTo(Depreciation::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

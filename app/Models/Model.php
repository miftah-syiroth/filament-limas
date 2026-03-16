<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Model extends EloquentModel implements HasMedia
{
    use HasUuids, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'model_number',
        'min_amount', // jumlah minimal yang harus ada di inventory untuk pembatasan stock, peminjaman dan alert
        'end_of_life', // int in months
        'manufacture_id',
        'category_id',
        'deprecation_id',
        'audit_interval', // in months
        'notes',
    ];
    
    protected $casts = [
        'audit_interval' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacture()
    {
        return $this->belongsTo(Manufacture::class);
    }

    public function deprecation()
    {
        return $this->belongsTo(Deprecation::class);
    }
}

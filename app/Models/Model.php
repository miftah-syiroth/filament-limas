<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'model_number',
        'min_amount', // jumlah minimal yang harus ada di inventory untuk pembatasan stock, peminjaman dan alert
        'end_of_life', // int in months
        'manufacture_id',
        'category_id',
        'deprecation_id', // no use for now
        'notes',
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

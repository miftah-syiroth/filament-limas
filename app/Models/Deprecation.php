<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deprecation extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'months',
        'depreciation_min',
        'depreciation_type',
        'notes',
    ];
}

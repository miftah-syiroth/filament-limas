<?php

namespace App\Models;

use App\Enums\DeprecationMethod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Deprecation extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'months',
        'minimum_value',
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
}

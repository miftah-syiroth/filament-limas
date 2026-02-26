<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'company_id',
        'address',
        'address2',
        'city',
        'state',
        'country',
        'zip',
        'phone',
        'notes',
        // manager_id. belum diimplementasikan
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}

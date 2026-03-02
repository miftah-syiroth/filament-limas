<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'code',
    ];

    public function provinces()
    {
        return $this->hasMany(Province::class, 'country_code', 'code');
    }
}

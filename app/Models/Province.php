<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'code', // string, unique
        'country_code', // string, e.g. 'ID' for Indonesia
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }
}

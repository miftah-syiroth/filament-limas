<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacture extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'support_url',
        'support_phone',
        'support_email',
        'warranty_lookup_url',
        'notes',
    ];
}

<?php

namespace App\Support;

use Illuminate\Support\Str;

class ItemSerialNumber
{
    public static function generate(): string
    {
        return strtoupper(substr(str_replace('-', '', Str::uuid()->toString()), 0, 8));
    }
}

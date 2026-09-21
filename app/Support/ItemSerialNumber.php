<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Str;

class ItemSerialNumber
{
    /**
     * @var (Closure(): string)|null
     */
    private static ?Closure $generator = null;

    public static function generate(): string
    {
        if (self::$generator !== null) {
            return (self::$generator)();
        }

        return strtoupper(substr(str_replace('-', '', Str::uuid()->toString()), 0, 8));
    }

    /**
     * @param  (Closure(): string)|null  $generator
     */
    public static function fake(?Closure $generator): void
    {
        self::$generator = $generator;
    }
}

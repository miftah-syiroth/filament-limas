<?php

namespace App\Filament\Resources\Deprecations\Pages;

use App\Filament\Resources\Deprecations\DeprecationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeprecation extends CreateRecord
{
    protected static string $resource = DeprecationResource::class;
}

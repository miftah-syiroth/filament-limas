<?php

namespace App\Filament\Resources\Deprecations\Pages;

use App\Filament\Resources\Deprecations\DeprecationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeprecation extends ViewRecord
{
    protected static string $resource = DeprecationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

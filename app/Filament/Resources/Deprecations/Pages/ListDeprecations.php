<?php

namespace App\Filament\Resources\Deprecations\Pages;

use App\Filament\Resources\Deprecations\DeprecationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeprecations extends ListRecords
{
    protected static string $resource = DeprecationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

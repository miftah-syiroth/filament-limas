<?php

namespace App\Filament\Resources\Manufactures\Pages;

use App\Filament\Resources\Manufactures\ManufactureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManufactures extends ListRecords
{
    protected static string $resource = ManufactureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

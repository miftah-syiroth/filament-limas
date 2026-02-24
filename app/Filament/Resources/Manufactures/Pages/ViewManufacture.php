<?php

namespace App\Filament\Resources\Manufactures\Pages;

use App\Filament\Resources\Manufactures\ManufactureResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewManufacture extends ViewRecord
{
    protected static string $resource = ManufactureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

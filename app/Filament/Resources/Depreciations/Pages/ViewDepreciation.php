<?php

namespace App\Filament\Resources\Depreciations\Pages;

use App\Filament\Resources\Depreciations\DepreciationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewDepreciation extends ViewRecord
{
    protected static string $resource = DepreciationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon(Heroicon::OutlinedPencil),
        ];
    }
}

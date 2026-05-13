<?php

namespace App\Filament\Resources\Depreciations\Pages;

use App\Filament\Resources\Depreciations\DepreciationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListDepreciations extends ListRecords
{
    protected static string $resource = DepreciationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('depreciation.form.add'))
                ->icon(Heroicon::OutlinedPlus),
        ];
    }
}

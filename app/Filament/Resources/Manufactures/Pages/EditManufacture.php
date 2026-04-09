<?php

namespace App\Filament\Resources\Manufactures\Pages;

use App\Filament\Resources\Manufactures\ManufactureResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditManufacture extends EditRecord
{
    protected static string $resource = ManufactureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon(Heroicon::OutlinedEye),
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

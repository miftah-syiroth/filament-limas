<?php

namespace App\Filament\Resources\Borrowings\Pages;

use App\Enums\BorrowingStatus;
use App\Filament\Resources\Borrowings\BorrowingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditBorrowing extends EditRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function getAllRelationManagers(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['returned_at'])) {
            $data['status'] = BorrowingStatus::Returned;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon(Heroicon::OutlinedEye),
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
            ForceDeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
            RestoreAction::make()
                ->icon(Heroicon::OutlinedArrowPath),
        ];
    }
}

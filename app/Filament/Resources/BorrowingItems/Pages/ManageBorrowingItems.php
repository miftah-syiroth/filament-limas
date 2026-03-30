<?php

namespace App\Filament\Resources\BorrowingItems\Pages;

use App\Filament\Resources\BorrowingItems\BorrowingItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBorrowingItems extends ManageRecords
{
    protected static string $resource = BorrowingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

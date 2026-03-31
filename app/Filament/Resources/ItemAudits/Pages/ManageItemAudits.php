<?php

namespace App\Filament\Resources\ItemAudits\Pages;

use App\Filament\Resources\ItemAudits\ItemAuditResource;
use Filament\Resources\Pages\ManageRecords;

class ManageItemAudits extends ManageRecords
{
    protected static string $resource = ItemAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}

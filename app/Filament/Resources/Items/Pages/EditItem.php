<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Items\ItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        if ($record->is_individual_tracking) {
            $data['tracking_entries'] = [
                [
                    'serial_number' => $record->serial_number,
                    'assignable_type' => $record->assignable_type,
                    'assignable_id' => $record->assignable_id,
                ],
            ];
        }

        return $data;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        if ($record->is_individual_tracking && isset($data['tracking_entries'])) {
            $first = is_array($data['tracking_entries']) ? reset($data['tracking_entries']) : null;
            if (is_array($first)) {
                $data['serial_number'] = $first['serial_number'] ?? $record->serial_number;
                $data['assignable_type'] = $first['assignable_type'] ?? null;
                $data['assignable_id'] = $first['assignable_id'] ?? null;
            }
            unset($data['tracking_entries']);
        }

        return $data;
    }
}

<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Items\ItemResource;
use App\Filament\Resources\Items\Schemas\ItemForm;
use App\Models\Item;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $isIndividualTracking = $data['is_individual_tracking'] ?? true;

        $baseData = collect($data)->except(['tracking_entries', 'serial_number'])->toArray();

        if ($isIndividualTracking) {
            $entries = $data['tracking_entries'] ?? [];
            $first = null;

            foreach ($entries as $entry) {
                $itemData = array_merge($baseData, [
                    'serial_number' => $entry['serial_number'],
                    'assignable_type' => $entry['assignable_type'] ?? null,
                    'assignable_id' => $entry['assignable_id'] ?? null,
                ]);
                $item = Item::create($itemData);
                $first ??= $item;
            }

            return $first ?? Item::create(array_merge($baseData, [
                'serial_number' => ItemForm::generateSerialNumber(),
                'assignable_type' => null,
                'assignable_id' => null,
            ]));
        }

        return Item::create(array_merge($baseData, [
            'serial_number' => $data['serial_number'],
            'assignable_type' => null,
            'assignable_id' => null,
        ]));
    }
}

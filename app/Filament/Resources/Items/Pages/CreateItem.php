<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\CategoryType;
use App\Enums\StockMovementType;
use App\Filament\Resources\Items\ItemResource;
use App\Filament\Resources\Items\Schemas\ItemForm;
use App\Models\Item;
use App\Models\Model as ItemModel;
use App\Models\StockMovement;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $model = ItemModel::find($data['model_id'] ?? null);
        $isConsumable = $model?->category?->type === CategoryType::Consumable;
        $isIndividualTracking = $isConsumable ? false : ($data['is_individual_tracking'] ?? true);

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

        $item = Item::create(array_merge($baseData, [
            'serial_number' => $data['serial_number'],
            'assignable_type' => null,
            'assignable_id' => null,
        ]));

        $quantity = (int) ($data['quantity'] ?? 1);
        if ($quantity > 0) {
            StockMovement::create([
                'item_id' => $item->id,
                'type' => StockMovementType::In,
                'quantity' => $quantity,
                'notes' => 'Stok awal',
            ]);
        }

        return $item;
    }
}

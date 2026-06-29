<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use App\Enums\StockMovementType;
use App\Filament\Resources\Items\ItemResource;
use App\Filament\Resources\Items\Schemas\ItemCreateForm;
use App\Models\Item;
use App\Models\Model as ItemModel;
use App\Models\StockMovement;
use App\Support\ItemSerialNumber;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    public function form(Schema $schema): Schema
    {
        return ItemCreateForm::configure($schema);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $model = ItemModel::with('category')->findOrFail($data['model_id']);
        $isConsumable = $model->category->type === CategoryType::Consumable;
        $isIndividualTracking = $isConsumable ? false : ($data['is_individual_tracking'] ?? true);
        $nextAuditDate = $model->computeInitialNextAuditDate();
        $status = $data['status'] ?? ItemStatus::Active;
        $first = null;

        foreach ($data['items'] ?? [] as $row) {
            if ($isIndividualTracking) {
                $count = (int) ($row['quantity'] ?? 1);

                for ($i = 0; $i < $count; $i++) {
                    $created = Item::create($this->itemAttributes($data, $model, $row, $isIndividualTracking, $status, $nextAuditDate, 1));
                    $first ??= $created;
                }

                continue;
            }

            $quantity = (int) ($row['quantity'] ?? 1);
            $created = Item::create($this->itemAttributes($data, $model, $row, $isIndividualTracking, $status, $nextAuditDate, $quantity));
            $first ??= $created;

            StockMovement::create([
                'item_id' => $created->id,
                'type' => StockMovementType::In,
                'quantity' => $quantity,
                'notes' => __('items.create.initial_stock_notes'),
            ]);
        }

        return $first ?? throw new RuntimeException('No items created.');
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function itemAttributes(
        array $data,
        ItemModel $model,
        array $row,
        bool $isIndividualTracking,
        ItemStatus $status,
        mixed $nextAuditDate,
        int $quantity,
    ): array {
        return [
            'model_id' => $data['model_id'],
            'serial_number' => ItemSerialNumber::generate(),
            'location_id' => $row['location_id'],
            'department_id' => $row['department_id'] ?? null,
            'room_id' => $row['room_id'] ?? null,
            'supplier_id' => $data['supplier_id'] ?? null,
            'name' => $model->name,
            'quantity' => $quantity,
            'purchase_date' => $data['purchase_date'] ?? null,
            'purchase_price' => $data['purchase_price'] ?? null,
            'eol_date' => $data['eol_date'] ?? null,
            'warranty_months' => $data['warranty_months'] ?? null,
            'is_individual_tracking' => $isIndividualTracking,
            'status' => $status,
            'next_audit_date' => $nextAuditDate,
        ];
    }
}

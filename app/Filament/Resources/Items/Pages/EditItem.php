<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\CategoryType;
use App\Filament\Resources\Items\ItemResource;
use App\Models\Model as ItemModel;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        $data['category_id'] = $record->model?->category_id;

        return $data;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();
        $model = ItemModel::find($data['model_id'] ?? $record->model_id);

        if ($model?->category?->type === CategoryType::Consumable) {
            $data['is_individual_tracking'] = false;
        }

        unset($data['tracking_entries']);

        return $data;
    }
}

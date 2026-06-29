<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\CategoryType;
use App\Filament\Resources\Items\ItemResource;
use App\Filament\Resources\Items\Schemas\ItemEditForm;
use App\Models\Model as ItemModel;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    public function form(Schema $schema): Schema
    {
        return ItemEditForm::configure($schema);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon(Heroicon::OutlinedEye),
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
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

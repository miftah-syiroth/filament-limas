<?php

namespace App\Filament\Resources\Models\Pages;

use App\Filament\Resources\Models\ModelResource;
use App\Filament\Resources\Models\Schemas\ModelForm;
use Filament\Resources\Pages\CreateRecord;

class CreateModel extends CreateRecord
{
    protected static string $resource = ModelResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return ModelForm::syncEndOfLifeFromDepreciation($data);
    }
}

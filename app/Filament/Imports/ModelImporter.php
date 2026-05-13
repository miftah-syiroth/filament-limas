<?php

namespace App\Filament\Imports;

use App\Models\Model;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ModelImporter extends Importer
{
    protected static ?string $model = Model::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('model_number')
                ->rules(['max:255', 'unique:models,model_number']),
            ImportColumn::make('min_amount')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('end_of_life')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('manufacture')
                ->relationship(resolveUsing: 'name'),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name'),
            ImportColumn::make('depreciation')
                ->relationship(resolveUsing: 'name'),
            ImportColumn::make('notes'),
            ImportColumn::make('audit_interval')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('unit')
                ->relationship(resolveUsing: 'name'),
        ];
    }

    public function resolveRecord(): Model
    {
        return Model::firstOrNew([
            'name' => $this->data['name'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your model import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}

<?php

namespace App\Filament\Imports;

use App\Models\Manufacture;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ManufactureImporter extends Importer
{
    protected static ?string $model = Manufacture::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(['Apple', 'Samsung', 'Honda', 'Toyota']),
            ImportColumn::make('url')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('support_url')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('support_phone')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('support_email')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('warranty_lookup_url')
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('notes'),
        ];
    }

    public function resolveRecord(): Manufacture
    {
        return Manufacture::firstOrNew([
            'name' => $this->data['name'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your manufacture import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}

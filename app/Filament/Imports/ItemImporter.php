<?php

namespace App\Filament\Imports;

use App\Enums\ItemStatus;
use App\Models\Department;
use App\Models\Item;
use App\Support\ItemSerialNumber;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class ItemImporter extends Importer
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('model')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(['required']),
            ImportColumn::make('location')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(['required']),
            ImportColumn::make('department')
                ->relationship(resolveUsing: function (string $state, array $data): ?Department {
                    $locationName = $data['location'] ?? null;
                    if (blank($locationName)) {
                        return null;
                    }

                    return Department::query()
                        ->where('name', $state)
                        ->whereHas('location', function ($query) use ($locationName) {
                            $query->where('name', $locationName);
                        })
                        ->first();
                }),
            ImportColumn::make('supplier')
                ->relationship(resolveUsing: 'name'),
            ImportColumn::make('name')
                ->rules(['max:255']),
            ImportColumn::make('serial_number')
                ->rules(['max:255']),
            ImportColumn::make('purchase_date')
                ->rules(['date']),
            ImportColumn::make('purchase_price')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('eol_date')
                ->rules(['date']),
            ImportColumn::make('warranty_months')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('is_individual_tracking')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->examples(array_map(
                    static fn (ItemStatus $status): string => $status->value,
                    ItemStatus::cases()
                ))
                ->rules(['required', 'max:20', Rule::enum(ItemStatus::class)]),
            ImportColumn::make('notes'),
            ImportColumn::make('quantity')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('user')
                ->relationship(resolveUsing: 'email'),
        ];
    }

    public function castData(): void
    {
        parent::castData();

        if (! array_key_exists('serial_number', $this->data) || blank($this->data['serial_number'])) {
            $this->data['serial_number'] = ItemSerialNumber::generate();
        }
    }

    public function resolveRecord(): Item
    {
        return Item::firstOrNew([
            'serial_number' => $this->data['serial_number'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your item import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}

<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\StockMovementType;
use App\Filament\Resources\Items\ItemResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageStockMovements extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string $relationship = 'stockMovements';

    protected static ?string $navigationLabel = 'Stock Movements';

    public static function canAccess(array $parameters = []): bool
    {
        $record = $parameters['record'] ?? null;

        if (! $record || $record->is_individual_tracking) {
            return false;
        }

        return parent::canAccess($parameters);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(StockMovementType::class)
                    ->required()
                    ->native(false)
                    ->live(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->rules([
                        fn (Get $get) => function (string $attribute, $value, $fail) use ($get) {
                            $value = (int) $value;
                            if ($value === 0) {
                                $fail('Kuantitas tidak boleh 0.');
                            }
                            $type = $get('type');
                            $typeValue = $type instanceof StockMovementType ? $type->value : $type;
                            if ($typeValue === StockMovementType::In->value && $value < 0) {
                                $fail('Kuantitas harus positif untuk tipe In.');
                            }
                            if ($typeValue === StockMovementType::Out->value && $value > 0) {
                                $fail('Kuantitas harus negatif untuk tipe Out.');
                            }
                        },
                    ]),
                Textarea::make('notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('notes')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

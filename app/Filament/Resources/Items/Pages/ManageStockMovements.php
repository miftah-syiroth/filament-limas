<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\StockMovementType;
use App\Filament\Resources\Items\ItemResource;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageStockMovements extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::InboxStack;

    protected static string $relationship = 'stockMovements';

    public static function getNavigationLabel(): string
    {
        return __('items.pages.stock_movements.navigation_label');
    }

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
                                $fail(__('items.pages.stock_movements.validation.quantity_not_zero'));
                            }
                            $type = $get('type');
                            $typeValue = $type instanceof StockMovementType ? $type->value : $type;
                            if ($typeValue === StockMovementType::In->value && $value < 0) {
                                $fail(__('items.pages.stock_movements.validation.in_must_positive'));
                            }
                            if ($typeValue === StockMovementType::Out->value && $value > 0) {
                                $fail(__('items.pages.stock_movements.validation.out_must_negative'));
                            }
                        },
                    ]),
                Textarea::make('notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', direction: 'desc')
            ->columns([
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('item.model.unit.name')
                    ->label(__('items.pages.stock_movements.unit'))
                    ->alignCenter(),
                TextColumn::make('notes')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->dateTime('j M Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('j M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()
                    ->authorize('create', $this->getOwnerRecord()),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\StockMovementType;
use App\Filament\Resources\Items\ItemResource;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;

class ManageStockMovements extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::InboxStack;

    protected static string $relationship = 'stockMovements';

    public function getTitle(): string | Htmlable
    {
        $item = $this->getOwnerRecord();
        return __('items.pages.stock_movements.title', [
            'item' => $item->serial_number ?? $item->name,
        ]);
    }

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
                        fn(Get $get) => function (string $attribute, $value, $fail) use ($get) {
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
                            if (! $this->getOwnerRecord()->canApplyStockMovement($value)) {
                                $fail(__('items.pages.stock_movements.validation.would_cause_negative_stock', [
                                    'balance' => $this->getOwnerRecord()->stockMovementBalance(),
                                ]));
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
                    ->alignCenter()
                    ->summarize(Sum::make()->hiddenLabel()),
                TextColumn::make('notes')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->dateTime('j M Y H:i')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label(__('items.table.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->native(false),
            ])
            ->headerActions([
                CreateAction::make()
                    ->authorize('create', $this->getOwnerRecord())
                    ->label(__('items.pages.stock_movements.add')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn(Collection $records) => $records->each->delete()),
                ]),
            ]);
    }
}

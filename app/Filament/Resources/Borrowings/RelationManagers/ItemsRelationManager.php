<?php

namespace App\Filament\Resources\Borrowings\RelationManagers;

use App\Enums\ItemAuditCondition;
use App\Filament\Infolists\Components\QrCodeEntry;
use App\Models\BorrowingItem;
use App\Models\Item;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->label(__('borrowing.relation.item'))
                    ->options(fn(?BorrowingItem $record): array => $this->getItemSelectOptions($record))
                    ->getSearchResultsUsing(fn(string $search, ?BorrowingItem $record): array => $this->searchItemSelectOptions($search, $record))
                    ->getOptionLabelUsing(function (?string $value): ?string {
                        if (! $value) {
                            return null;
                        }

                        $item = Item::with('model')->find($value);

                        return $item ? $this->formatItemOptionLabel($item) : null;
                    })
                    ->preload()
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state): void {
                        $set('quantity', null);
                        $set('condition_out', null);
                        if ($state) {
                            $item = Item::with('latestAudit')->find($state);
                            $set('borrowable_quantity', $item?->borrowableQuantity);
                            $set('quantity', $item?->borrowableQuantity ?? 1);
                            $set('condition_out', $item?->latestAudit?->condition?->value);
                        }
                    })
                    ->columnSpanFull(),
                TextInput::make('borrowable_quantity')
                    ->label(__('borrowing.relation.borrowable_quantity'))
                    ->required()
                    ->disabled(),
                TextInput::make('quantity')
                    ->label(__('borrowing.relation.quantity'))
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(fn(Get $get): ?int => $get('borrowable_quantity')),
                DatePicker::make('checked_out_at')
                    ->label(__('borrowing.relation.checked_out_at'))
                    ->default(now()->toDateString())
                    ->required(),
                Select::make('condition_out')
                    ->label(__('borrowing.relation.condition_out'))
                    ->options(ItemAuditCondition::class)
                    ->native(false)
                    ->required(),
                DatePicker::make('checked_in_at')
                    ->label(__('borrowing.relation.checked_in_at'))
                    ->live()
                    ->required(fn(Get $get): bool => ! empty($get('condition_in'))),
                Select::make('condition_in')
                    ->label(__('borrowing.relation.condition_in'))
                    ->options(ItemAuditCondition::class)
                    ->native(false)
                    ->live()
                    ->required(fn(Get $get): bool => ! empty($get('checked_in_at'))),
                Textarea::make('notes')
                    ->label(__('borrowing.relation.notes'))
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('borrowing.relation.table_heading'))
            ->columns([
                TextColumn::make('item.serial_number')
                    ->label(__('borrowing.relation.serial_number')),
                TextColumn::make('item.model.name')
                    ->label(__('borrowing.relation.model')),
                TextColumn::make('quantity')
                    ->label(__('borrowing.relation.quantity'))
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('checked_out_at')
                    ->label(__('borrowing.relation.checked_out_at'))
                    ->dateTime('j M Y'),
                TextColumn::make('condition_out')
                    ->label(__('borrowing.relation.condition_out'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('checked_in_at')
                    ->label(__('borrowing.relation.checked_in_at'))
                    ->dateTime('j M Y'),
                TextColumn::make('condition_in')
                    ->label(__('borrowing.relation.condition_in'))
                    ->badge()
                    ->color('primary'),
                TextColumn::make('notes')
                    ->label(__('borrowing.relation.notes'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('borrowing.relation.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('borrowing.relation.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('borrowing.relation.add_item'))
                    ->closeModalByClickingAway(false)
                    ->using(function (array $data): BorrowingItem {
                        $data['from_location_id'] = $this->getOwnerRecord()->to_location_id;
                        $data['from_department_id'] = $this->getOwnerRecord()->to_department_id;
                        $data['from_room_id'] = $this->getOwnerRecord()->to_room_id;
                        $data['to_location_id'] = $this->getOwnerRecord()->from_location_id;
                        $data['to_department_id'] = $this->getOwnerRecord()->from_department_id;
                        $data['to_room_id'] = $this->getOwnerRecord()->from_room_id;

                        return $this->getOwnerRecord()->items()->create($data);
                    }),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->modalSubmitAction(false)
                    ->modalWidth('lg')
                    ->schema([
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        QrCodeEntry::make('qr_code')
                                            ->state(fn(BorrowingItem $record): string => $record->item->serial_number)
                                            ->hiddenLabel(),
                                    ])
                                    ->columnSpan(1),
                                Grid::make(1)
                                    ->schema([
                                        TextEntry::make('item.serial_number')
                                            ->label(__('borrowing.relation.serial_number'))
                                            ->badge(),
                                        TextEntry::make('quantity')
                                            ->label(__('borrowing.relation.quantity'))
                                            ->numeric(),
                                    ])
                                    ->columnSpan(1),
                            ]),
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                Fieldset::make(__('borrowing.relation.modal_fieldset_out'))
                                    ->columns(1)
                                    ->schema([
                                        TextEntry::make('checked_out_at')
                                            ->label(__('borrowing.relation.modal_date'))
                                            ->dateTime('j M Y'),
                                        TextEntry::make('condition_out')
                                            ->hiddenLabel()
                                            ->badge()
                                            ->color('gray'),
                                    ]),
                                Fieldset::make(__('borrowing.relation.modal_fieldset_in'))
                                    ->columns(1)
                                    ->schema([
                                        TextEntry::make('checked_in_at')
                                            ->label(__('borrowing.relation.modal_date'))
                                            ->dateTime('j M Y'),
                                        TextEntry::make('condition_in')
                                            ->hiddenLabel()
                                            ->badge()
                                            ->color('primary'),
                                    ]),
                            ]),
                        TextEntry::make('notes')
                            ->label(__('borrowing.relation.notes'))
                            ->columnSpanFull()
                            ->placeholder('-'),
                    ]),
                EditAction::make()
                    ->hiddenLabel()
                    ->mutateRecordDataUsing(function (array $data, BorrowingItem $record): array {
                        $item = Item::with('activeBorrowingItems')->find($record->item_id);

                        $data['borrowable_quantity'] = ($item?->borrowableQuantity ?? 0) + $record->quantity;

                        return $data;
                    }),
            ])
            ->filters([
                TrashedFilter::make()
                    ->native(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('return')
                        ->label(__('borrowing.relation.return_items'))
                        ->icon(Heroicon::DocumentCheck)
                        ->color('primary')
                        ->authorizeIndividualRecords('update')
                        ->fillForm(function (): array {
                            return [
                                'checked_in_at' => now()->toDateString(),
                            ];
                        })
                        ->schema([
                            DatePicker::make('checked_in_at')
                                ->label(__('borrowing.relation.modal_date'))
                                ->required(),
                            Select::make('condition_in')
                                ->label(__('borrowing.relation.condition_in'))
                                ->options(ItemAuditCondition::class)
                                ->native(false)
                                ->required(),
                            Textarea::make('notes')
                                ->label(__('borrowing.relation.notes'))
                                ->columnSpanFull(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $recordIds = $records->pluck('id');
                            BorrowingItem::whereIn('id', $recordIds)->update($data);
                        })
                        ->successNotificationTitle(__('borrowing.relation.return_items_success'))
                        ->failureNotificationTitle(function (int $successCount, int $totalCount): string {
                            if ($successCount) {
                                return __('borrowing.relation.return_items_failure', ['successCount' => $successCount, 'totalCount' => $totalCount]);
                            }
                    
                            return __('borrowing.relation.return_items_failure', ['successCount' => 0, 'totalCount' => $totalCount]);
                        }),
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn(Collection $records) => $records->each->delete()),
                    ForceDeleteBulkAction::make()
                        ->authorizeIndividualRecords('forceDelete')
                        ->action(fn(Collection $records) => $records->each->forceDelete()),
                ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    protected function getItemSelectOptions(?BorrowingItem $record = null): array
    {
        return $this->itemSelectQuery($record)
            ->limit(50)
            ->get()
            ->mapWithKeys(fn(Item $item): array => [
                $item->id => $this->formatItemOptionLabel($item),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    protected function searchItemSelectOptions(string $search, ?BorrowingItem $record = null): array
    {
        return $this->itemSelectQuery($record)
            ->where(function (Builder $query) use ($search): void {
                $query->where('serial_number', 'ilike', "%{$search}%")
                    ->orWhereHas('model', fn(Builder $q) => $q->where('name', 'ilike', "%{$search}%"));
            })
            ->limit(50)
            ->get()
            ->mapWithKeys(fn(Item $item): array => [
                $item->id => $this->formatItemOptionLabel($item),
            ])
            ->all();
    }

    protected function itemSelectQuery(?BorrowingItem $record): Builder
    {
        $excludedItemIds = $this->getOwnerRecord()->items
            ->when($record, fn($items) => $items->where('id', '!=', $record->id))
            ->pluck('item_id');

        return Item::query()
            ->with('model')
            ->where(function (Builder $query) use ($record): void {
                $query->borrowable();

                if ($record?->item_id) {
                    $query->orWhere('id', $record->item_id);
                }
            })
            ->when($excludedItemIds->isNotEmpty(), fn(Builder $query) => $query->whereNotIn('id', $excludedItemIds));
    }

    protected function formatItemOptionLabel(Item $item): string
    {
        return "{$item->serial_number} - {$item->model->name}";
    }
}

<?php

namespace App\Filament\Resources\Borrowings\Pages;

use App\Filament\Resources\Borrowings\BorrowingResource;
use App\Filament\Resources\Models\ModelResource;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Department;
use App\Models\Item;
use App\Models\Location;
use App\Models\Room;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateBorrowing extends CreateRecord implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string $resource = BorrowingResource::class;

    protected string $view = 'pages.filament.resources.borrowings.pages.create-borrowing';

    /**
     * @var array<string, int>
     */
    public array $quantitiesToBorrow = [];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pilih Item')
            ->query($this->getTableQuery())
            ->defaultSort('created_at', direction: 'desc')
            ->columns([
                TextColumn::make('serial_number')
                    ->label(__('items.table.serial_number'))
                    ->searchable(),
                TextColumn::make('borrowable')
                    ->label(__('items.table.borrowable_quantity'))
                    ->state(function (Model $record): int {
                        return max(0, $record->quantity - $record->activeBorrowingItems->sum('quantity'));
                    })
                    ->numeric()
                    ->alignCenter(),
                TextInputColumn::make('quantity_to_borrow')
                    ->label(__('borrowing.form.quantity'))
                    ->type('number')
                    ->inputMode('numeric')
                    ->state(fn (Item $record): ?string => isset($this->quantitiesToBorrow[$record->getKey()])
                        ? (string) $this->quantitiesToBorrow[$record->getKey()]
                        : null)
                    ->updateStateUsing(function (Item $record, mixed $state): ?int {
                        if (blank($state)) {
                            unset($this->quantitiesToBorrow[$record->getKey()]);

                            return null;
                        }

                        $quantity = (int) $state;
                        $borrowable = $this->getBorrowableQuantity($record);

                        if ($quantity < 1) {
                            throw ValidationException::withMessages([
                                'quantity_to_borrow' => __('validation.min.numeric', [
                                    'attribute' => __('borrowing.form.quantity'),
                                    'min' => 1,
                                ]),
                            ]);
                        }

                        if ($quantity > $borrowable) {
                            throw ValidationException::withMessages([
                                'quantity_to_borrow' => __('validation.max.numeric', [
                                    'attribute' => __('borrowing.form.quantity'),
                                    'max' => $borrowable,
                                ]),
                            ]);
                        }

                        $this->quantitiesToBorrow[$record->getKey()] = $quantity;

                        return $quantity;
                    }),
                TextColumn::make('model.category.name')
                    ->label(__('items.table.category')),
                TextColumn::make('model.manufacture.name')
                    ->label(__('items.table.manufacturer')),
                TextColumn::make('model.name')
                    ->label(__('items.table.model'))
                    ->searchable()
                    ->url(fn (Model $record): string => ModelResource::getUrl('view', ['record' => $record->model])),
                TextColumn::make('model.category.type')
                    ->label(__('items.table.type'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location.name')
                    ->label(__('items.table.location'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('department.name')
                    ->label(__('items.table.department'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('room.name')
                    ->label(__('items.table.room'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_name')
                    ->label(__('items.table.category'))
                    ->relationship('model.category', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('manufacturer_name')
                    ->label(__('items.table.manufacturer'))
                    ->relationship('model.manufacture', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('model_name')
                    ->label(__('items.table.model'))
                    ->relationship(
                        'model',
                        'name'
                    )
                    ->preload()
                    ->searchable(),
                SelectFilter::make('location_name')
                    ->label(__('items.table.location'))
                    ->relationship('location', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('department_name')
                    ->label(__('items.table.department'))
                    ->relationship('department', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('room_name')
                    ->label(__('items.table.room'))
                    ->relationship('room', 'name')
                    ->preload()
                    ->searchable(),
            ])
            ->filtersFormColumns(3)
            ->toolbarActions([
                BulkAction::make('selectItemToBorrow')
                    ->label('Buat Pinjaman Barang')
                    ->modalWidth(Width::ScreenExtraLarge)
                    ->closeModalByClickingAway(false)
                    ->icon(Heroicon::CursorArrowRipple)
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])
                            ->schema([
                                DatePicker::make('borrowed_at')
                                    ->label(__('borrowing.form.borrowed_at'))
                                    ->required()
                                    ->default(now()->format('m/d/Y')),
                                DatePicker::make('due_at')
                                    ->label(__('borrowing.form.due_at'))
                                    ->required()
                                    ->after('borrowed_at'),
                                Select::make('to_location_id')
                                    ->label(__('borrowing.form.to_location'))
                                    ->options(Location::pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('to_department_id', null);
                                        $set('to_room_id', null);
                                    })
                                    ->preload(),
                                Select::make('to_department_id')
                                    ->label(__('borrowing.form.to_department'))
                                    ->options(function (Get $get): SupportCollection {
                                        $locationId = $get('to_location_id');
                                        if (! $locationId) {
                                            return collect();
                                        }

                                        return Department::whereHas('locations', fn (Builder $query) => $query->where('location_id', $locationId))
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload(),
                                Select::make('to_room_id')
                                    ->label(__('borrowing.form.to_room'))
                                    ->options(function (Get $get): SupportCollection {
                                        $locationId = $get('to_location_id');
                                        if (! $locationId) {
                                            return collect();
                                        }

                                        return Room::where('location_id', $locationId)
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload(),
                                Textarea::make('notes')
                                    ->label(__('borrowing.form.notes')),
                            ]),
                    ])
                    ->action(function (BulkAction $action, Collection $records, array $data): void {
                        $invalidSerialNumbers = $this->getInvalidQuantitySerialNumbers($records);

                        if ($invalidSerialNumbers !== []) {
                            Notification::make()
                                ->danger()
                                ->title(__('borrowing.notifications.invalid_quantities_title'))
                                ->body(__('borrowing.notifications.invalid_quantities_body', [
                                    'items' => implode(', ', $invalidSerialNumbers),
                                ]))
                                ->send();

                            $action->halt();
                        }

                        $borrowing = $this->createBorrowingFromSelectedItems($records, $data);

                        Notification::make()
                            ->title(__('borrowing.notifications.created'))
                            ->success()
                            ->send();

                        $this->redirect(BorrowingResource::getUrl('view', ['record' => $borrowing]));
                    }),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return Item::query()
            ->borrowable()
            ->with(['activeBorrowingItems' => function ($query) {
                $query->whereNull('checked_in_at');
            }, 'latestAudit', 'model']);
    }

    protected function getBorrowableQuantity(Item $record): int
    {
        return max(0, $record->quantity - $record->activeBorrowingItems->sum('quantity'));
    }

    /**
     * @return list<string>
     */
    protected function getInvalidQuantitySerialNumbers(Collection $records): array
    {
        $invalidSerialNumbers = [];

        foreach ($records as $record) {
            $quantity = $this->quantitiesToBorrow[$record->getKey()] ?? null;

            if (blank($quantity) || (int) $quantity < 1) {
                $invalidSerialNumbers[] = $record->serial_number;

                continue;
            }

            if ((int) $quantity > $this->getBorrowableQuantity($record)) {
                $invalidSerialNumbers[] = $record->serial_number;
            }
        }

        return $invalidSerialNumbers;
    }

    protected function createBorrowingFromSelectedItems(Collection $records, array $data): Borrowing
    {
        $items = $records->map(fn (Item $record): array => [
            'item' => $record,
            'quantity' => $this->quantitiesToBorrow[$record->getKey()],
        ]);

        return DB::transaction(function () use ($items, $data): Borrowing {
            $borrowing = Borrowing::create($data);

            foreach ($items as $item) {
                BorrowingItem::create([
                    'borrowing_id' => $borrowing->id,
                    'item_id' => $item['item']->id,
                    'quantity' => $item['quantity'],
                    'checked_out_at' => $data['borrowed_at'],
                    'condition_out' => $item['item']->latestAudit?->condition?->value ?? null,
                    'from_location_id' => $item['item']->location_id,
                    'from_department_id' => $item['item']->department_id,
                    'from_room_id' => $item['item']->room_id,
                    'to_location_id' => $data['to_location_id'],
                    'to_department_id' => $data['to_department_id'],
                    'to_room_id' => $data['to_room_id'],
                ]);
            }

            return $borrowing;
        });
    }
}

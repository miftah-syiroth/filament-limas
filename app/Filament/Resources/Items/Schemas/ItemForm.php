<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use App\Models\Category;
use App\Models\Model as ItemModel;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ItemForm
{
    public static function generateSerialNumber(): string
    {
        return strtoupper(substr(str_replace('-', '', Str::uuid()->toString()), 0, 8));
    }

    public static function isCategoryConsumable(Get $get): bool
    {
        return Category::find($get('category_id'))?->type === CategoryType::Consumable;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label(__('items.form.category'))
                            ->options(function (Get $get, Component $component): array {
                                return Category::get()
                                    ->mapWithKeys(fn (Category $category) => [
                                        $category->id => "{$category->name} - {$category->type?->getLabel()}",
                                    ])
                                    ->all();
                            })
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Component $component): void {
                                $set('model_id', null);
                                $category = Category::find($state);
                                $set('type', $category?->type?->getLabel() ?? '-');
                                if ($category?->type === CategoryType::Consumable) {
                                    $set('is_individual_tracking', false);
                                } elseif ($category?->type !== CategoryType::Consumable) {
                                    $set('is_individual_tracking', true);
                                    $set('quantity', 1);
                                }
                            })
                            ->required()
                            ->native(false),
                        Select::make('model_id')
                            ->label(__('items.form.model'))
                            ->relationship(
                                name: 'model',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query
                                    ->with('manufacture')
                                    ->when(
                                        $get('category_id'),
                                        fn (Builder $q): Builder => $q->where('category_id', $get('category_id')),
                                        fn (Builder $q): Builder => $q->whereRaw('1 = 0'),
                                    )
                            )
                            ->getOptionLabelFromRecordUsing(fn (ItemModel $record): string => $record->manufacture
                                ? "{$record->name} - {$record->manufacture->name}"
                                : $record->name)
                            ->disabled(fn (Get $get): bool => blank($get('category_id')))
                            ->required()
                            ->native(false),
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                            'md' => 3,
                        ])
                            ->columnSpanFull()
                            ->schema([
                                Select::make('location_id')
                                    ->label(__('items.form.location'))
                                    ->relationship('location', 'name')
                                    ->required()
                                    ->live()
                                    ->disabled(fn (Component $component): bool => $component->getContainer()?->getOperation() === 'edit')
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('department_id', null);
                                        $set('room_id', null);
                                    })
                                    ->native(false),
                                Select::make('department_id')
                                    ->label(__('items.form.department'))
                                    ->relationship(
                                        name: 'department',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query
                                            ->when(
                                                $get('location_id'),
                                                fn (Builder $q): Builder => $q->where('location_id', $get('location_id')),
                                                fn (Builder $q): Builder => $q->whereRaw('1 = 0'),
                                            )
                                    )
                                    ->disabled(fn (Component $component): bool => $component->getContainer()?->getOperation() === 'edit')
                                    ->searchable()
                                    ->preload()
                                    ->key('department_id'),
                                Select::make('room_id')
                                    ->label(__('items.form.room'))
                                    ->relationship(
                                        name: 'room',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query
                                            ->when(
                                                $get('location_id'),
                                                fn (Builder $q): Builder => $q->where('location_id', $get('location_id')),
                                                fn (Builder $q): Builder => $q->whereRaw('1 = 0'),
                                            )
                                    )
                                    ->disabled(fn (Component $component): bool => $component->getContainer()?->getOperation() === 'edit')
                                    ->searchable()
                                    ->preload()
                                    ->key('room_id'),
                            ]),
                        Flex::make([
                            Select::make('status')
                                ->label(__('items.form.status'))
                                ->options(ItemStatus::class)
                                ->default(ItemStatus::Active)
                                ->disabled(fn (Component $component): bool => $component->getContainer()?->getOperation() === 'edit')
                                ->native(false)
                                ->required(),
                            Toggle::make('is_individual_tracking')
                                ->required()
                                ->label(__('items.form.individual_tracking'))
                                ->inline(false)
                                // defaultnya adalah true jika kategori bukan consumable
                                ->default(fn (Get $get) => self::isCategoryConsumable($get) ? false : true)
                                ->live()
                                ->afterStateUpdated(fn (Set $set, $state) => $state ? $set('quantity', 1) : null)
                                ->disabled(fn (Get $get) => self::isCategoryConsumable($get))
                                ->saved()
                                ->rules([
                                    function (Get $get) {
                                        return function ($attribute, $value, $fail) use ($get) {
                                            if (self::isCategoryConsumable($get) && $value === true) {
                                                $fail(__('items.form.validation.consumable_no_individual'));
                                            }
                                        };
                                    },
                                ])
                                ->grow(false),
                            TextInput::make('quantity')
                                ->label(__('items.form.quantity'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->disabled(fn (Get $get, Component $component) => $component->getContainer()?->getOperation() === 'edit' || $get('is_individual_tracking') === true)
                                ->saved()
                                ->rules([
                                    function (Get $get) {
                                        return function ($attribute, $value, $fail) use ($get) {
                                            if (self::isCategoryConsumable($get) && (int) $value < 1) {
                                                $fail(__('items.form.validation.quantity_positive_consumable'));
                                            }
                                            if ($get('is_individual_tracking') === true && (int) $value !== 1) {
                                                $fail(__('items.form.validation.quantity_one_when_individual'));
                                            }
                                        };
                                    },
                                ]),
                        ])
                            ->columnSpanFull()
                            ->from('sm'),
                        TextInput::make('name')
                            ->label(__('items.form.name')),
                        Textarea::make('notes')
                            ->label(__('items.form.notes')),
                    ]),
                Section::make(__('items.form.sections.serial_number'))
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('is_individual_tracking') === true)
                    ->hiddenOn('edit')
                    ->schema([
                        Repeater::make('tracking_entries')
                            ->schema([
                                TextInput::make('serial_number')
                                    ->required()
                                    ->unique(table: 'items', column: 'serial_number', ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(fn () => self::generateSerialNumber()),
                                Select::make('user_id')
                                    ->label(__('items.form.responsible_person'))
                                    ->options(fn (): array => User::query()->pluck('name', 'id')->toArray())
                                    ->nullable()
                                    ->searchable()
                                    ->native(false),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel(__('items.form.add_serial')),
                    ]),
                Section::make(__('items.form.sections.serial_number'))
                    ->columnSpanFull()
                    ->visible(fn (Get $get, string $operation) => $get('is_individual_tracking') === false || $operation === 'edit')
                    ->schema([
                        TextInput::make('serial_number')
                            ->required()
                            ->unique(table: 'items', column: 'serial_number', ignoreRecord: true)
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => self::generateSerialNumber()),
                        Select::make('user_id')
                            ->label(__('items.form.responsible_person'))
                            ->options(fn (): array => User::query()->pluck('name', 'id')->toArray())
                            ->disabled(fn (Component $component): bool => $component->getContainer()?->getOperation() === 'edit')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ])->columns(2),
                Section::make(__('items.form.sections.images'))
                    ->hiddenOn('create')
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->disk('public')
                            ->hiddenLabel()
                            ->collection('images')
                            ->multiple()
                            ->image()
                            ->maxSize(1024)
                            ->maxFiles(3)
                            ->columnSpanFull(),
                    ]),
                Section::make(__('items.form.sections.purchase'))
                    ->description(__('items.form.sections.purchase_description'))
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->iconColor('info')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 3,
                    ])
                    ->schema([
                        Select::make('supplier_id')
                            ->label(__('items.form.supplier'))
                            ->relationship('supplier', 'name')
                            ->native(false),
                        DatePicker::make('purchase_date')
                            ->label(__('items.form.purchase_date')),
                        TextInput::make('purchase_price')
                            ->label(__('items.form.purchase_price'))
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp'),
                        DatePicker::make('eol_date')
                            ->label(__('items.form.eol_date')),
                        TextInput::make('warranty_months')
                            ->label(__('items.form.warranty_months'))
                            ->minValue(0)
                            ->numeric(),
                        TextInput::make('order_quantity')
                            ->label(__('items.form.order_quantity'))
                            ->default(1)
                            ->minValue(1)
                            ->numeric(),
                    ]),

            ]);
    }
}

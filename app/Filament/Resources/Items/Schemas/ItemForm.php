<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Enums\ItemStatus;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ItemForm
{
    public static function generateSerialNumber(): string
    {
        return strtoupper(substr(str_replace('-', '', Str::uuid()->toString()), 0, 8));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('model_id')
                            ->relationship('model', 'name')
                            ->required()
                            ->native(false),
                        Select::make('location_id')
                            ->relationship('location', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Select $component) => $component
                                ->getContainer()
                                ->getComponent('department_id')
                                ->state(null))
                            ->native(false),
                        Select::make('department_id')
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
                            ->live()
                            ->native(false)
                            ->key('department_id'),
                        TextInput::make('name'),
                        Select::make('status')
                            ->options(ItemStatus::class)
                            ->native(false)
                            ->required(),
                        Toggle::make('is_individual_tracking')
                            ->required()
                            ->label('Pelacakan Individu')
                            ->inline(false)
                            ->default(true)
                            ->live()
                            ->afterStateUpdated(fn (Set $set, $state) => $state ? $set('quantity', 1) : null)
                            ->disabledOn('edit'),
                        TextInput::make('quantity')
                            ->label('Kuantitas')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(1)
                            ->disabled(fn (Get $get, Component $component) => $component->getContainer()?->getOperation() === 'edit' || $get('is_individual_tracking') === true)
                            ->dehydrated()
                            ->rules([
                                function (Get $get) {
                                    return function ($attribute, $value, $fail) use ($get) {
                                        if ($get('is_individual_tracking') === true && (int) $value !== 1) {
                                            $fail('Kuantitas harus 1 jika individual tracking aktif.');
                                        }
                                    };
                                },
                            ]),
                        Textarea::make('notes'),
                    ]),
                Section::make('Pelacakan')
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
                                Select::make('assignable_type')
                                    ->label('Tipe Assignable')
                                    ->options([
                                        'App\\Models\\User' => 'User',
                                    ])
                                    ->nullable()
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('assignable_id', null)),
                                Select::make('assignable_id')
                                    ->label('Assignable')
                                    ->options(fn (Get $get): array => $get('assignable_type') === 'App\\Models\\User'
                                        ? User::query()->pluck('name', 'id')->toArray()
                                        : [])
                                    ->nullable()
                                    ->searchable()
                                    ->native(false),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel('Tambah Serial'),
                    ]),
                Section::make('Pelacakan')
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('is_individual_tracking') === false)
                    ->schema([
                        TextInput::make('serial_number')
                            ->required()
                            ->unique(table: 'items', column: 'serial_number', ignoreRecord: true)
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => self::generateSerialNumber()),
                    ]),
                Section::make('Informasi Pembelian')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->native(false),
                        TextInput::make('order_quantity')
                            ->numeric(),
                        DatePicker::make('purchase_date'),
                        TextInput::make('purchase_price')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('warranty_months')
                            ->label('Garansi (bulan)')
                            ->numeric(),
                        DatePicker::make('eol_date')
                            ->label('Kadaluarsa'),
                    ]),

            ]);
    }
}

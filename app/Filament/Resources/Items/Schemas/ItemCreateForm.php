<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use App\Filament\Resources\Items\Schemas\Concerns\InteractsWithItemCategory;
use App\Models\Category;
use App\Models\Model as ItemModel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ItemCreateForm
{
    use InteractsWithItemCategory;

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
                            ->options(function (): array {
                                return Category::query()->get()
                                    ->mapWithKeys(fn (Category $category) => [
                                        $category->id => "{$category->name} - {$category->type->getLabel()}",
                                    ])
                                    ->all();
                            })
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state): void {
                                $set('model_id', null);
                                $category = Category::find($state);
                                if ($category?->type === CategoryType::Consumable) {
                                    $set('is_individual_tracking', false);
                                } elseif ($category?->type !== CategoryType::Consumable) {
                                    $set('is_individual_tracking', true);
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
                        Select::make('status')
                            ->label(__('items.form.status'))
                            ->options(ItemStatus::class)
                            ->default(ItemStatus::Active)
                            ->native(false)
                            ->required(),
                        Toggle::make('is_individual_tracking')
                            ->required()
                            ->label(__('items.form.individual_tracking'))
                            ->inline(false)
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
                            ]),
                    ]),
                Section::make()
                    ->compact()
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->hiddenLabel()
                            ->schema([
                                Select::make('location_id')
                                    ->label(__('items.form.location'))
                                    ->relationship('location', 'name')
                                    ->required()
                                    ->live()
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
                                                fn (Builder $q): Builder => $q->whereHas('locations', fn (Builder $q): Builder => $q->where('location_id', $get('location_id'))),
                                                fn (Builder $q): Builder => $q->whereRaw('1 = 0'),
                                            )
                                    )
                                    ->searchable()
                                    ->preload(),
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
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('quantity')
                                    ->label(__('items.form.quantity'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->saved(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->required()
                            ->addActionLabel(__('items.form.add_item')),
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
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label(__('supplier.form.name'))
                                    ->required(),
                            ])
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
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Models\Schemas;

use App\Enums\CategoryType;
use App\Enums\DepreciationMethod;
use App\Models\Depreciation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class ModelForm
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function syncEndOfLifeFromDepreciation(array $data): array
    {
        if (blank($data['depreciation_id'] ?? null)) {
            return $data;
        }

        $depreciation = Depreciation::query()->find($data['depreciation_id']);

        if ($depreciation !== null) {
            $data['end_of_life'] = $depreciation->months;
        }

        return $data;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make()
                            ->columnSpan(2)
                            ->columns(2)
                            ->compact()
                            ->schema([
                                Select::make('category_id')
                                    ->label(__('model.form.category'))
                                    ->relationship(
                                        name: 'category',
                                    )
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name} - {$record->type->getLabel()}")
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label(__('category.form.name'))
                                            ->required(),
                                        Select::make('type')
                                            ->label(__('category.form.type'))
                                            ->options(CategoryType::class)
                                            ->native(false)
                                            ->required(),
                                    ])
                                    ->native(false)
                                    ->required(),
                                Select::make('unit_id')
                                    ->label(__('model.form.unit'))
                                    ->relationship('unit', 'name')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label(__('unit.form.name'))
                                            ->required(),
                                    ])
                                    ->native(false)
                                    ->required(),
                                TextInput::make('name')
                                    ->label(__('model.form.name'))
                                    ->required(),
                                TextInput::make('model_number')
                                    ->label(__('model.form.model_number')),
                                Select::make('manufacture_id')
                                    ->label(__('model.form.manufacturer'))
                                    ->relationship('manufacture', 'name')
                                    ->searchable()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label(__('manufacture.form.name'))
                                            ->required(),
                                    ])
                                    ->preload()
                                    ->required(),
                                Select::make('depreciation_id')
                                    ->label(__('model.form.depreciation'))
                                    ->relationship('depreciation', 'name')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label(__('depreciation.form.name'))
                                            ->required(),
                                        TextInput::make('months')
                                            ->label(__('depreciation.form.months'))
                                            ->required()
                                            ->minValue(1)
                                            ->numeric(),
                                        TextInput::make('minimum_value')
                                            ->label(__('depreciation.form.minimum_value'))
                                            ->belowContent([
                                                Icon::make(Heroicon::OutlinedInformationCircle),
                                                __('depreciation.form.minimum_value_helper')
                                            ])
                                            ->required()
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(100)
                                            ->suffix('%'),
                                        Select::make('method')
                                            ->label(__('depreciation.form.method'))
                                            ->options(DepreciationMethod::class)
                                            ->native(false)
                                            ->default(DepreciationMethod::Amount->value)
                                            ->required(),
                                    ])
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                                        if (blank($state)) {
                                            $set('end_of_life', null);
                                            return;
                                        }

                                        $depreciation = Depreciation::query()->find($state);
                                        $set('end_of_life', $depreciation?->months);
                                    }),
                                TextInput::make('min_amount')
                                    ->label(__('model.form.min_amount'))
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(0)
                                    ->required()
                                    ->belowContent(__('model.form.min_amount_helper')),
                                TextInput::make('end_of_life')
                                    ->label(__('model.form.end_of_life'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix(__('model.form.months_suffix'))
                                    ->belowContent(__('model.form.end_of_life_helper'))
                                    ->disabled(fn(Get $get): bool => filled($get('depreciation_id')))
                                    ->saved(fn(Get $get): bool => filled($get('depreciation_id'))),
                                TextInput::make('audit_interval')
                                    ->label(__('model.form.audit_interval'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix(__('model.form.months_suffix')),
                                Textarea::make('notes')
                                    ->label(__('model.form.notes'))
                                    ->columnSpanFull(),
                            ]),
                        Section::make()
                            ->columns(2)
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->disk('public')
                                    ->hiddenLabel()
                                    ->multiple()
                                    ->image()
                                    // ->maxSize(1024)
                                    ->maxFiles(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}

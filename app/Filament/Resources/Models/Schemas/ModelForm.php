<?php

namespace App\Filament\Resources\Models\Schemas;

use App\Models\Depreciation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

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
                            ->schema([
                                Select::make('category_id')
                                    ->label(__('model.form.category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('unit_id')
                                    ->label(__('model.form.unit'))
                                    ->relationship('unit', 'name')
                                    ->searchable()
                                    ->preload()
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
                                    ->preload()
                                    ->required(),
                                Select::make('depreciation_id')
                                    ->label(__('model.form.depreciation'))
                                    ->relationship('depreciation', 'name')
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
                                    ->required()
                                    ->belowContent(__('model.form.min_amount_helper')),
                                TextInput::make('end_of_life')
                                    ->label(__('model.form.end_of_life'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix(__('model.form.months_suffix'))
                                    ->belowContent(__('model.form.end_of_life_helper'))
                                    ->disabled(fn (Get $get): bool => filled($get('depreciation_id')))
                                    ->saved(fn (Get $get): bool => filled($get('depreciation_id'))),
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
                                    ->maxSize(1024)
                                    ->maxFiles(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}

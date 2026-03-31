<?php

namespace App\Filament\Resources\Models\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModelForm
{
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
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('unit_id')
                                    ->label('Satuan')
                                    ->relationship('unit', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('name')
                                    ->required(),
                                Select::make('manufacture_id')
                                    ->relationship('manufacture', 'name')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('model_number'),
                                TextInput::make('min_amount')
                                    ->label('Minimal Stock')
                                    ->numeric()
                                    ->belowContent('Jumlah stock minimal yang harus ada.'),
                                TextInput::make('end_of_life')
                                    ->label('Masa Pakai')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('bulan'),
                                Select::make('deprecation_id')
                                    ->relationship('deprecation', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(null),
                                TextInput::make('audit_interval')
                                    ->label('Interval Audit')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('bulan'),
                                Textarea::make('notes')
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

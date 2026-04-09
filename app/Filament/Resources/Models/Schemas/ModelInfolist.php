<?php

namespace App\Filament\Resources\Models\Schemas;

use App\Enums\CategoryType;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make(__('model.infolist.section_information'))
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label(__('model.infolist.name')),
                                TextEntry::make('unit.name')
                                    ->label(__('model.infolist.unit'))
                                    ->placeholder('-'),
                                TextEntry::make('model_number')
                                    ->label(__('model.infolist.model_number'))
                                    ->placeholder('-'),
                                TextEntry::make('manufacture.name')
                                    ->label(__('model.infolist.manufacturer'))
                                    ->placeholder('-'),
                                TextEntry::make('category.name')
                                    ->label(__('model.infolist.category'))
                                    ->placeholder('-'),
                                TextEntry::make('category.type')
                                    ->label(__('model.infolist.category_type'))
                                    ->formatStateUsing(fn (?CategoryType $state): string => $state instanceof CategoryType ? (string) $state->getLabel() : '')
                                    ->badge()
                                    ->color(fn ($state): string => $state instanceof CategoryType ? $state->getColor() : 'gray')
                                    ->placeholder('-'),
                                TextEntry::make('end_of_life')
                                    ->label(__('model.infolist.end_of_life'))
                                    ->numeric()
                                    ->suffix(__('model.infolist.months_suffix'))
                                    ->placeholder('-'),
                                TextEntry::make('audit_interval')
                                    ->label(__('model.infolist.audit_interval'))
                                    ->numeric()
                                    ->suffix(__('model.infolist.months_suffix'))
                                    ->placeholder('-'),
                                TextEntry::make('deprecation.months')
                                    ->label(__('model.infolist.depreciation_period'))
                                    ->numeric()
                                    ->suffix(__('model.infolist.months_suffix'))
                                    ->placeholder('-'),
                                TextEntry::make('deprecation.minimum_value')
                                    ->label(__('model.infolist.minimum_value'))
                                    ->placeholder('-'),

                                TextEntry::make('min_amount')
                                    ->label(__('model.infolist.min_amount'))
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('notes')
                                    ->label(__('model.infolist.notes'))
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),
                        Section::make(__('model.infolist.section_images'))
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('images')
                                    ->hiddenLabel(),
                            ]),
                    ]),
            ]);
    }
}

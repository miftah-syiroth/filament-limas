<?php

namespace App\Filament\Resources\Models\Schemas;

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
                        Section::make('Informasi Model')
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama'),
                                TextEntry::make('unit.name')
                                    ->label('Satuan')
                                    ->placeholder('-'),
                                TextEntry::make('model_number')
                                    ->label('Nomor Model')
                                    ->placeholder('-'),
                                TextEntry::make('manufacture.name')
                                    ->label('Manufacture')
                                    ->placeholder('-'),
                                TextEntry::make('category.name')
                                    ->label('Kategori')
                                    ->placeholder('-'),
                                TextEntry::make('category.type')
                                    ->label('Tipe Kategori')
                                    ->badge()
                                    ->color(fn ($state): string => $state?->getColor() ?? 'gray')
                                    ->placeholder('-'),
                                TextEntry::make('end_of_life')
                                    ->label('Masa Pakai')
                                    ->numeric()
                                    ->suffix(' bulan')
                                    ->placeholder('-'),
                                TextEntry::make('audit_interval')
                                    ->label('Interval Audit')
                                    ->numeric()
                                    ->suffix(' bulan')
                                    ->placeholder('-'),
                                TextEntry::make('deprecation.months')
                                    ->label('Masa Depresiasi')
                                    ->numeric()
                                    ->suffix(' bulan')
                                    ->placeholder('-'),
                                TextEntry::make('deprecation.minimum_value')
                                    ->label('Nilai Minimum')
                                    ->placeholder('-'),

                                TextEntry::make('min_amount')
                                    ->label('Minimal Stock')
                                    ->numeric()
                                    ->placeholder('-'),

                                TextEntry::make('notes')
                                    ->label('Catatan')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Gambar')
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('images')
                                    ->hiddenLabel(),
                            ]),
                    ]),
            ]);
    }
}

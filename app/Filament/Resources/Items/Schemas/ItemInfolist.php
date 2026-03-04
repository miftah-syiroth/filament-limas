<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Enums\AssignableType;
use App\Filament\Infolists\Components\QrCodeEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make('Informasi Umum')
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('serial_number')
                                    ->inlineLabel(),
                                TextEntry::make('name')
                                    ->label('Nama')
                                    ->inlineLabel(),
                                TextEntry::make('model.name')
                                    ->label('Model')
                                    ->inlineLabel(),
                                TextEntry::make('model.category.type')
                                    ->label('Tipe')
                                    ->inlineLabel()
                                    ->badge()
                                    ->color(fn ($state) => $state->getColor()),
                                TextEntry::make('model.category.name')
                                    ->label('Kategori')
                                    ->inlineLabel(),
                                IconEntry::make('is_individual_tracking')
                                    ->label('Pelacakan Individu')
                                    ->boolean()
                                    ->inlineLabel(),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->inlineLabel(),
                                TextEntry::make('location.name')
                                    ->label('Location')
                                    ->inlineLabel(),
                                TextEntry::make('department.name')
                                    ->label('Department')
                                    ->inlineLabel(),
                                TextEntry::make('notes')
                                    ->inlineLabel(),

                            ]),
                        Section::make('')
                            ->schema([
                                QrCodeEntry::make('serial_number')
                                    ->hiddenLabel(),
                                TextEntry::make('assignable')
                                    ->label('Pengguna')
                                    ->formatStateUsing(fn ($state) => $state?->name ?? '-')
                                    ->inlineLabel(),
                                TextEntry::make('assignable_type')
                                    ->label('Peran')
                                    ->formatStateUsing(fn ($state) => $state instanceof AssignableType ? $state->getLabel() : '-')
                                    ->inlineLabel(),
                            ]),
                    ]),
                Section::make('Informasi Pembelian')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('supplier.name')
                            ->label('Supplier')
                            ->inlineLabel(),
                        TextEntry::make('purchase_date')
                            ->dateTime()
                            ->inlineLabel(),
                        TextEntry::make('order_quantity')
                            ->numeric()
                            ->inlineLabel(),
                        TextEntry::make('purchase_price')
                            ->money('IDR')
                            ->inlineLabel(),
                        TextEntry::make('warranty_months')
                            ->label('Garansi bulan')
                            ->inlineLabel()
                            ->numeric()
                            ->suffix('bulan'),
                        TextEntry::make('eol_date')
                            ->label('End of Life')
                            ->dateTime()
                            ->inlineLabel(),
                    ]),
            ]);
    }
}

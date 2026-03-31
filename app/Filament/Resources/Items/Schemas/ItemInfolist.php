<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Filament\Infolists\Components\QrCodeEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
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
                            ->columnOrder([
                                'default' => 2,
                                'lg' => 1,
                            ])
                            ->schema([
                                TextEntry::make('serial_number')
                                    ->inlineLabel(),
                                TextEntry::make('name')
                                    ->label('Nama')
                                    ->inlineLabel(),
                                TextEntry::make('model.name')
                                    ->label('Model')
                                    ->inlineLabel(),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
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
                                TextEntry::make('notes')
                                    ->inlineLabel(),

                                Grid::make(2)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('quantity')
                                            ->label('Kuantitas')
                                            ->formatStateUsing(fn ($state, $record): string => $record->model->unit?->name
                                                ? "{$state} {$record->model?->unit?->name}"
                                                : (string) $state),
                                        TextEntry::make('borrowable_quantity')
                                            ->label('Dapat Dipinjam')
                                            ->numeric(),
                                        TextEntry::make('user.name')
                                            ->label('Penanggung Jawab'),
                                        TextEntry::make('department.name')
                                            ->label('Department'),
                                        TextEntry::make('location.name')
                                            ->label('Location'),
                                        TextEntry::make('latestAudit.audited_at')
                                            ->label('Audit Terakhir')
                                            ->dateTime('j M Y'),
                                        TextEntry::make('latestAudit.next_audit_at')
                                            ->label('Audit Berikutnya')
                                            ->dateTime('j M Y'),
                                    ]),

                            ]),
                        Section::make('')
                            ->columnOrder([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                QrCodeEntry::make('serial_number')
                                    ->hiddenLabel(),
                                SpatieMediaLibraryImageEntry::make('images')
                                    ->collection('images')
                                    ->hiddenLabel(),
                            ]),
                    ]),
                Section::make('Informasi Pembelian')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('purchase_date')
                                        ->date()
                                        ->inlineLabel(),
                                    TextEntry::make('purchase_price')
                                        ->money('IDR')
                                        ->inlineLabel(),
                                    TextEntry::make('deprecated_price')
                                        ->money('IDR')
                                        ->inlineLabel(),
                                    TextEntry::make('eol_date')
                                        ->label('End of Life')
                                        ->dateTime()
                                        ->inlineLabel(),
                                ]),
                                Group::make([
                                    TextEntry::make('supplier.name')
                                        ->label('Supplier')
                                        ->inlineLabel(),
                                    TextEntry::make('order_quantity')
                                        ->numeric()
                                        ->inlineLabel(),
                                    TextEntry::make('warranty_months')
                                        ->label('Garansi bulan')
                                        ->inlineLabel()
                                        ->numeric()
                                        ->suffix('bulan'),
                                ]),
                            ]),

                    ]),
            ]);
    }
}

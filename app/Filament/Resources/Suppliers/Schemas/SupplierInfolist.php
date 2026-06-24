<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('supplier.infolist.name')),
                        TextEntry::make('address')
                            ->label(__('supplier.infolist.address'))
                            ->placeholder('-'),
                        TextEntry::make('address2')
                            ->label(__('supplier.infolist.address2'))
                            ->placeholder('-'),
                        TextEntry::make('city')
                            ->label(__('supplier.infolist.city'))
                            ->placeholder('-'),
                        TextEntry::make('province')
                            ->label(__('supplier.infolist.province'))
                            ->placeholder('-'),
                        TextEntry::make('country')
                            ->label(__('supplier.infolist.country'))
                            ->placeholder('-'),
                        TextEntry::make('zip')
                            ->label(__('supplier.infolist.zip'))
                            ->placeholder('-'),
                        TextEntry::make('phone')
                            ->label(__('supplier.infolist.phone'))
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label(__('supplier.infolist.email'))
                            ->placeholder('-'),
                        TextEntry::make('url')
                            ->label(__('supplier.infolist.url'))
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->label(__('supplier.infolist.notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),

                    ]),
            ]);
    }
}

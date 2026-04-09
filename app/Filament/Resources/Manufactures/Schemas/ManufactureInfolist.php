<?php

namespace App\Filament\Resources\Manufactures\Schemas;

use App\Models\Manufacture;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class ManufactureInfolist
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
                            ->label(__('manufacture.infolist.name')),
                        TextEntry::make('url')
                            ->label(__('manufacture.infolist.url'))
                            ->placeholder('-'),
                        TextEntry::make('support_url')
                            ->label(__('manufacture.infolist.support_url'))
                            ->placeholder('-'),
                        TextEntry::make('support_phone')
                            ->label(__('manufacture.infolist.support_phone'))
                            ->placeholder('-'),
                        TextEntry::make('support_email')
                            ->label(__('manufacture.infolist.support_email'))
                            ->placeholder('-'),
                        TextEntry::make('warranty_lookup_url')
                            ->label(__('manufacture.infolist.warranty_lookup_url'))
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->label(__('manufacture.infolist.notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

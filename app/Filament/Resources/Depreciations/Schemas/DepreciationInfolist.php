<?php

namespace App\Filament\Resources\Depreciations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepreciationInfolist
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
                            ->label(__('depreciation.infolist.name')),
                        TextEntry::make('months')
                            ->label(__('depreciation.infolist.months'))
                            ->numeric(),
                        TextEntry::make('minimum_value')
                            ->label(__('depreciation.infolist.minimum_value'))
                            ->numeric(),
                        TextEntry::make('method')
                            ->label(__('depreciation.infolist.method')),
                        TextEntry::make('notes')
                            ->columnSpanFull()
                            ->label(__('depreciation.infolist.notes'))
                            ->html(),
                    ])
            ]);
    }
}

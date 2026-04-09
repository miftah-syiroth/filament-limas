<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Enums\CategoryType;
use App\Models\Category;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryInfolist
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
                            ->label(__('category.infolist.name')),
                        TextEntry::make('type')
                            ->label(__('category.infolist.type'))
                            ->formatStateUsing(fn(?CategoryType $state): string => $state instanceof CategoryType ? (string) $state->getLabel() : ''),
                        TextEntry::make('notes')
                            ->label(__('category.infolist.notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('organization.name')
                            ->label(__('department.infolist.organization'))
                            ->placeholder('-'),
                        TextEntry::make('locations.name')
                            ->label(__('department.infolist.location'))
                            ->placeholder('-')
                            ->separator(',')
                            ->badge(),
                        TextEntry::make('name')
                            ->label(__('department.infolist.name')),
                        TextEntry::make('phone')
                            ->label(__('department.infolist.phone'))
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->label(__('department.infolist.notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

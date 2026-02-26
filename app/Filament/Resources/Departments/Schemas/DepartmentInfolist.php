<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Department;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('company.name')
                    ->label('Company')
                    ->placeholder('-'),
                TextEntry::make('location.name')
                    ->label('Location')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Department $record): bool => $record->trashed()),
            ]);
    }
}

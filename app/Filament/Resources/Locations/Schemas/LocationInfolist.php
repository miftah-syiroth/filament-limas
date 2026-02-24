<?php

namespace App\Filament\Resources\Locations\Schemas;

use App\Models\Location;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LocationInfolist
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
                TextEntry::make('name'),
                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('address2')
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->placeholder('-'),
                TextEntry::make('state')
                    ->placeholder('-'),
                TextEntry::make('country')
                    ->placeholder('-'),
                TextEntry::make('zip')
                    ->placeholder('-'),
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
                    ->visible(fn (Location $record): bool => $record->trashed()),
            ]);
    }
}

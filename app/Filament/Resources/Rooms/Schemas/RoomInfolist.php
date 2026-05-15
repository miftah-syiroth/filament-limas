<?php

namespace App\Filament\Resources\Rooms\Schemas;

use App\Models\Room;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RoomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('room.infolist.id')),
                TextEntry::make('location.name')
                    ->label(__('room.infolist.location'))
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->label(__('room.infolist.name')),
                TextEntry::make('capacity')
                    ->label(__('room.infolist.capacity'))
                    ->numeric(),
                TextEntry::make('notes')
                    ->label(__('room.infolist.notes'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label(__('room.infolist.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

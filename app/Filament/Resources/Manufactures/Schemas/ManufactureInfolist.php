<?php

namespace App\Filament\Resources\Manufactures\Schemas;

use App\Models\Manufacture;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ManufactureInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('name'),
                TextEntry::make('url')
                    ->placeholder('-'),
                TextEntry::make('support_url')
                    ->placeholder('-'),
                TextEntry::make('support_phone')
                    ->placeholder('-'),
                TextEntry::make('support_email')
                    ->placeholder('-'),
                TextEntry::make('warranty_lookup_url')
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
                    ->visible(fn (Manufacture $record): bool => $record->trashed()),
            ]);
    }
}

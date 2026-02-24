<?php

namespace App\Filament\Resources\Models\Schemas;

use App\Models\Model;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ModelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('name'),
                TextEntry::make('model_number')
                    ->placeholder('-'),
                TextEntry::make('min_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('end_of_life')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('require_serial_number')
                    ->boolean(),
                TextEntry::make('manufacture_id')
                    ->placeholder('-'),
                TextEntry::make('category_id')
                    ->placeholder('-'),
                TextEntry::make('deprecation_id')
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
                    ->visible(fn (Model $record): bool => $record->trashed()),
            ]);
    }
}

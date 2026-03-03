<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Models\Item;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('model.name')
                    ->label('Model'),
                TextEntry::make('location.name')
                    ->label('Location'),
                TextEntry::make('department.name')
                    ->label('Department')
                    ->placeholder('-'),
                TextEntry::make('supplier.name')
                    ->label('Supplier')
                    ->placeholder('-'),
                TextEntry::make('assignable_id')
                    ->placeholder('-'),
                TextEntry::make('assignable_type')
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->placeholder('-'),
                TextEntry::make('sku')
                    ->label('SKU')
                    ->placeholder('-'),
                TextEntry::make('serial_number')
                    ->placeholder('-'),
                TextEntry::make('order_quantity')
                    ->numeric(),
                TextEntry::make('purchase_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('purchase_price')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('eol_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('warranty_months')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_individual_tracking')
                    ->boolean(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status_updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Item $record): bool => $record->trashed()),
            ]);
    }
}

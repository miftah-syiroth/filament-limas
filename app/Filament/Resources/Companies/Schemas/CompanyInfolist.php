<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Models\Company;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyInfolist
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
                            ->label(__('company.infolist.name')),
                        TextEntry::make('email')
                            ->label(__('company.infolist.email')),
                        TextEntry::make('phone')
                            ->label(__('company.infolist.phone')),
                        TextEntry::make('notes')
                            ->label(__('company.infolist.notes')),
                        TextEntry::make('created_at')
                            ->label(__('company.infolist.created_at'))
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label(__('company.infolist.updated_at'))
                            ->dateTime(),
                        TextEntry::make('deleted_at')
                            ->label(__('company.infolist.deleted_at'))
                            ->dateTime()
                            ->visible(fn(Company $record): bool => $record->trashed()),

                    ])
            ]);
    }
}

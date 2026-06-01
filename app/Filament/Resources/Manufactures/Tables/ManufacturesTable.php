<?php

namespace App\Filament\Resources\Manufactures\Tables;

use App\Filament\Imports\ManufactureImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManufacturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('manufacture.table.name'))
                    ->searchable(),
                TextColumn::make('url')
                    ->label(__('manufacture.table.url'))
                    ->searchable(),
                TextColumn::make('support_url')
                    ->label(__('manufacture.table.support_url'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('support_phone')
                    ->label(__('manufacture.table.support_phone'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('support_email')
                    ->label(__('manufacture.table.support_email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('warranty_lookup_url')
                    ->label(__('manufacture.table.warranty_lookup_url'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('models_count')
                    ->label(__('manufacture.table.models_count'))
                    ->counts('models'),
                TextColumn::make('created_at')
                    ->label(__('manufacture.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(ManufactureImporter::class)
                    ->label(__('manufacture.actions.import'))
                    ->icon(Heroicon::OutlinedArrowUpTray),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

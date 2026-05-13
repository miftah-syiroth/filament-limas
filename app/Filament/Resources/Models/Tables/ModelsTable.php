<?php

namespace App\Filament\Resources\Models\Tables;

use App\Enums\CategoryType;
use App\Filament\Imports\ModelImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->recordUrl(null)
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->label(__('model.table.images'))
                    ->limit(1),
                TextColumn::make('name')
                    ->label(__('model.table.name'))
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->label(__('model.table.unit'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('manufacture.name')
                    ->label(__('model.table.manufacturer')),
                TextColumn::make('category.name')
                    ->label(__('model.table.category')),
                TextColumn::make('category.type')
                    ->label(__('model.table.category_type'))
                    ->formatStateUsing(fn (?CategoryType $state): string => $state instanceof CategoryType ? (string) $state->getLabel() : '')
                    ->badge(),
                TextColumn::make('audit_interval')
                    ->label(__('model.table.audit_interval'))
                    ->numeric()
                    ->suffix(__('model.table.months_suffix')),
                TextColumn::make('model_number')
                    ->label(__('model.table.model_number'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('min_amount')
                    ->label(__('model.table.min_amount'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_of_life')
                    ->label(__('model.table.end_of_life'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
                EditAction::make()
                    ->label(''),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(ModelImporter::class)
                    ->label(__('model.actions.import'))
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

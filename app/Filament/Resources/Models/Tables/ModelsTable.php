<?php

namespace App\Filament\Resources\Models\Tables;

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
use Filament\Tables\Filters\TrashedFilter;
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
                    ->limit(1),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->label('Satuan')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('manufacture.name'),
                TextColumn::make('category.name'),
                TextColumn::make('category.type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('audit_interval')
                    ->label('Interval Audit')
                    ->numeric()
                    ->suffix(' bulan'),
                TextColumn::make('model_number')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('min_amount')
                    ->label('Jumlah minimal')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_of_life')
                    ->label('Kadaluarsa')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(ModelImporter::class)
                    ->label('Import')
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

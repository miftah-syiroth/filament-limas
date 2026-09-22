<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Enums\CategoryType;
use App\Filament\Imports\CategoryImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('category.table.name'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('category.table.type'))
                    ->badge(),
                TextColumn::make('models_count')
                    ->label(__('category.table.models_count'))
                    ->counts('models')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label(__('category.table.created_at'))
                    ->dateTime(format: 'j M Y H:i:s', timezone: 'Asia/Jakarta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filtersFormColumns(2)
            ->filters([
                SelectFilter::make('type')
                    ->label(__('category.table.type'))
                    ->options(CategoryType::class)
                    ->native(false)
                    ->query(function (Builder $query, array $data): Builder {
                        $type = $data['value'] ?? null;
                        if (empty($type)) {
                            return $query;
                        }
                        return $query->where('type', $type);
                    }),
                TrashedFilter::make()
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
                EditAction::make()
                    ->label(''),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(CategoryImporter::class)
                    ->label(__('category.actions.import'))
                    ->icon(Heroicon::OutlinedArrowUpTray),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn (Collection $records) => $records->each->delete()),
                ]),
            ]);
    }
}

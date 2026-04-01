<?php

namespace App\Filament\Resources\Items\Tables;

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use App\Filament\Imports\ItemImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', direction: 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('images')
                    ->limit(1),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('model.name')
                    ->searchable(),
                TextColumn::make('model.category.name'),
                TextColumn::make('model.category.type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('quantity')
                    ->label('Total')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('borrowable_quantity')
                    ->label('Tersedia')
                    ->numeric()
                    ->alignCenter(),
                IconColumn::make('is_individual_tracking')
                    ->label('Individu')
                    ->alignCenter()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(ItemStatus::class),
                SelectFilter::make('type')
                    ->label('Tipe Kategori')
                    ->multiple()
                    ->options(CategoryType::class)
                    ->query(function (Builder $query, array $data): Builder {
                        $types = $data['values'] ?? [];
                        if (empty($types)) {
                            return $query;
                        }

                        return $query->whereHas('model', function (Builder $q) use ($types): Builder {
                            return $q->whereHas('category', fn (Builder $q): Builder => $q->whereIn('type', $types));
                        });
                    }),
                TernaryFilter::make('is_individual_tracking')
                    ->label('Individu'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(ItemImporter::class)
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

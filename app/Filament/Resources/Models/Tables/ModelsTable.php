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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

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
                TextColumn::make('model_number')
                    ->label(__('model.table.model_number'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->label(__('model.table.category')),
                TextColumn::make('category.type')
                    ->label(__('model.table.category_type'))
                    ->formatStateUsing(fn (?CategoryType $state): string => $state instanceof CategoryType ? (string) $state->getLabel() : '')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('manufacture.name')
                    ->label(__('model.table.manufacturer')),
                TextColumn::make('depreciation')
                    ->label(__('model.table.depreciation'))
                    ->state(function (Model $model): ?string {
                        if ($depreciation = $model->depreciation) {
                            return $depreciation->months.' | '.Number::format($depreciation->minimum_value, 0).'%';
                        }

                        return null;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('audit_interval')
                    ->label(__('model.table.audit_interval'))
                    ->numeric()
                    ->sortable()
                    ->suffix(__('model.table.months_suffix')),
                TextColumn::make('min_amount')
                    ->label(__('model.table.min_amount'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_of_life')
                    ->label(__('model.table.end_of_life'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit.name')
                    ->label(__('model.table.unit'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('items_count')
                    ->label(__('model.table.items_count'))
                    ->counts('items')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('model.table.category_type'))
                    ->multiple()
                    ->options(CategoryType::class)
                    ->query(function (Builder $query, array $data): Builder {
                        $types = $data['values'] ?? [];
                        if (empty($types)) {
                            return $query;
                        }

                        return $query->whereHas('category', function (Builder $query) use ($types): Builder {
                            return $query->whereIn('type', $types);
                        });
                    }),
                SelectFilter::make('category_name')
                    ->label(__('model.table.category'))
                    ->relationship('category', 'name')
                    ->multiple(),
                SelectFilter::make('manufacture_name')
                    ->label(__('model.table.manufacturer'))
                    ->relationship('manufacture', 'name')
                    ->multiple(),
                SelectFilter::make('depreciation_name')
                    ->label(__('model.table.depreciation'))
                    ->relationship('depreciation', 'name', hasEmptyOption: true)
                    ->emptyRelationshipOptionLabel(__('model.table.no_depreciation'))
                    ->multiple(),
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

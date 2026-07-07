<?php

namespace App\Filament\Resources\BorrowingItems;

use App\Enums\ItemAuditCondition;
use App\Enums\NavigationGroup;
use App\Filament\Exports\BorrowingItemExporter;
use App\Filament\Resources\BorrowingItems\Pages\ManageBorrowingItems;
use App\Filament\Resources\Borrowings\BorrowingResource;
use App\Filament\Resources\Items\ItemResource;
use App\Models\BorrowingItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BorrowingItemResource extends Resource
{
    protected static ?string $model = BorrowingItem::class;

    public static function getModelLabel(): string
    {
        return __('borrowing-item.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('borrowing-item.plural_model_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('borrowing-item.navigation_label');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item.serial_number')
                    ->label(__('borrowing-item.infolist.item_serial'))
                    ->icon(Heroicon::ArrowUpRight)
                    ->iconColor('primary')
                    ->url(fn(BorrowingItem $record) => ItemResource::getUrl('view', ['record' => $record->item])),
                TextEntry::make('item.model.name')
                    ->label(__('borrowing-item.infolist.model_name')),
                TextEntry::make('borrowing.id')
                    ->label(__('borrowing-item.infolist.borrowing'))
                    ->icon(Heroicon::ArrowUpRight)
                    ->iconColor('primary')
                    ->url(fn(BorrowingItem $record) => BorrowingResource::getUrl('view', ['record' => $record->borrowing])),
                TextEntry::make('borrowing.status')
                    ->label(__('borrowing.infolist.status'))
                    ->badge(),
                TextEntry::make('quantity')
                    ->label(__('borrowing-item.infolist.quantity'))
                    ->numeric(),
                TextEntry::make('borrowing.toLocation.name')
                    ->label(__('borrowing.infolist.to_location'))
                    ->placeholder('-'),
                TextEntry::make('borrowing.toDepartment.name')
                    ->label(__('borrowing.infolist.to_department'))
                    ->placeholder('-'),
                TextEntry::make('borrowing.toRoom.name')
                    ->label(__('borrowing.infolist.to_room'))
                    ->placeholder('-'),
                TextEntry::make('checked_out_at')
                    ->label(__('borrowing-item.infolist.checked_out_at'))
                    ->dateTime('j M Y')
                    ->placeholder('-'),
                TextEntry::make('condition_out')
                    ->label(__('borrowing-item.infolist.condition_out'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('checked_in_at')
                    ->label(__('borrowing-item.infolist.checked_in_at'))
                    ->dateTime('j M Y')
                    ->placeholder('-'),
                TextEntry::make('condition_in')
                    ->label(__('borrowing-item.infolist.condition_in'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->label(__('borrowing-item.infolist.notes'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label(__('borrowing-item.infolist.created_at'))
                    ->dateTime('j M Y H:i')
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('item.serial_number')
                    ->label(__('borrowing-item.table.item_serial'))
                    ->searchable(),
                TextColumn::make('item.model.name')
                    ->label(__('borrowing-item.table.model_name')),
                TextColumn::make('borrowing.user.email')
                    ->label(__('borrowing-item.table.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('quantity')
                    ->label(__('borrowing-item.table.quantity'))
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('checked_out_at')
                    ->label(__('borrowing-item.table.checked_out_at'))
                    ->dateTime('j M Y'),
                TextColumn::make('condition_out')
                    ->label(__('borrowing-item.table.condition_out'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('checked_in_at')
                    ->label(__('borrowing-item.table.checked_in_at'))
                    ->dateTime('j M Y'),
                TextColumn::make('condition_in')
                    ->label(__('borrowing-item.table.condition_in'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('borrowing.toLocation.name')
                    ->label(__('borrowing.table.to_location'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('borrowing.toDepartment.name')
                    ->label(__('borrowing.table.to_department'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('borrowing.toRoom.name')
                    ->label(__('borrowing.table.to_room'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('borrowing-item.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('borrowing-item.table.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->closeModalByClickingAway(false),
            ])
            ->filters([
                SelectFilter::make('condition_in')
                    ->label(__('borrowing-item.filters.condition_in'))
                    ->multiple()
                    ->options(ItemAuditCondition::class),
                TrashedFilter::make()
                    ->native(false),
            ])
            ->filtersFormColumns(2)
            ->headerActions([
                ExportAction::make()
                    ->exporter(BorrowingItemExporter::class)
                    ->label(__('borrowing-item.actions.export'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->fileDisk('public'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn(Collection $records) => $records->each->delete()),
                    ForceDeleteBulkAction::make()
                        ->authorizeIndividualRecords('forceDelete')
                        ->action(fn(Collection $records) => $records->each->forceDelete()),
                    RestoreBulkAction::make()
                        ->authorizeIndividualRecords('restore')
                        ->action(fn(Collection $records) => $records->each->restore()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBorrowingItems::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

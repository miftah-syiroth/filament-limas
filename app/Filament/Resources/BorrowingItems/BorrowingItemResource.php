<?php

namespace App\Filament\Resources\BorrowingItems;

use App\Enums\ItemAuditCondition;
use App\Filament\Exports\BorrowingItemExporter;
use App\Filament\Resources\BorrowingItems\Pages\ManageBorrowingItems;
use App\Models\BorrowingItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item.serial_number')
                    ->label(__('borrowing-item.infolist.item_serial')),
                TextEntry::make('borrowing.id')
                    ->label(__('borrowing-item.infolist.borrowing')),
                TextEntry::make('quantity')
                    ->label(__('borrowing-item.infolist.quantity'))
                    ->numeric(),
                TextEntry::make('checked_out_at')
                    ->label(__('borrowing-item.infolist.checked_out_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('checked_in_at')
                    ->label(__('borrowing-item.infolist.checked_in_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('condition_in')
                    ->label(__('borrowing-item.infolist.condition_in'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('condition_out')
                    ->label(__('borrowing-item.infolist.condition_out'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->label(__('borrowing-item.infolist.notes'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label(__('borrowing-item.infolist.created_at'))
                    ->dateTime()
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
                TextColumn::make('borrowing.user.name')
                    ->label(__('borrowing-item.table.borrower'))
                    ->searchable(),
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
            ])
            ->filters([
                SelectFilter::make('condition_in')
                    ->label(__('borrowing-item.filters.condition_in'))
                    ->multiple()
                    ->options(ItemAuditCondition::class),

            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(BorrowingItemExporter::class)
                    ->label(__('borrowing-item.actions.export'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->fileDisk('public'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
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

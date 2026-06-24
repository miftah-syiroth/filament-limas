<?php

namespace App\Filament\Resources\ItemAudits;

use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use App\Enums\NavigationGroup;
use App\Filament\Exports\ItemAuditExporter;
use App\Filament\Resources\ItemAudits\Pages\ManageItemAudits;
use App\Filament\Resources\Items\ItemResource;
use App\Models\ItemAudit;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ItemAuditResource extends Resource
{
    protected static ?string $model = ItemAudit::class;

    public static function getModelLabel(): string
    {
        return __('item-audit.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('item-audit.plural_model_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('item-audit.navigation_label');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item.serial_number')
                    ->label(__('item-audit.infolist.item')),
                TextEntry::make('item.status')
                    ->label(__('item-audit.infolist.status'))
                    ->badge(),
                IconEntry::make('location_verified')
                    ->label(__('item-audit.infolist.location_verified'))
                    ->boolean(),
                TextEntry::make('notes')
                    ->label(__('item-audit.infolist.notes'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('audited_at')
                    ->label(__('item-audit.infolist.audited_at'))
                    ->date('j M Y')
                    ->placeholder('-'),
                TextEntry::make('next_audit_at')
                    ->label(__('item-audit.infolist.next_audit_at'))
                    ->date('j M Y')
                    ->placeholder('-'),
                TextEntry::make('condition')
                    ->label(__('item-audit.infolist.condition'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('result')
                    ->label(__('item-audit.infolist.result'))
                    ->badge()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('audited_at', direction: 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label(__('item-audit.table.code'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('id', 'ilike', "%{$search}%");
                    }),
                TextColumn::make('item.serial_number')
                    ->label(__('item-audit.table.item'))
                    ->searchable()
                    ->url(fn (ItemAudit $record): string => ItemResource::getUrl('view', ['record' => $record->item])),
                TextColumn::make('item.model.name')
                    ->label(__('item-audit.table.model'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('item.model.category.name')
                    ->label(__('item-audit.table.category'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('audited_at')
                    ->label(__('item-audit.table.audited_at'))
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('next_audit_at')
                    ->label(__('item-audit.table.next_audit_at'))
                    ->date('j M Y')
                    ->sortable(),
                IconColumn::make('location_verified')
                    ->label(__('item-audit.table.location_verified'))
                    ->boolean(),
                TextColumn::make('condition')
                    ->label(__('item-audit.table.condition'))
                    ->badge(),
                TextColumn::make('result')
                    ->label(__('item-audit.table.result'))
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('condition')
                    ->label(__('item-audit.filters.condition'))
                    ->multiple()
                    ->options(ItemAuditCondition::class),
                SelectFilter::make('result')
                    ->label(__('item-audit.filters.result'))
                    ->multiple()
                    ->options(ItemAuditResult::class),
                SelectFilter::make('category_name')
                    ->label(__('item-audit.table.category'))
                    ->relationship('item.model.category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('model_name')
                    ->label(__('item-audit.table.model'))
                    ->relationship('item.model', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->filtersFormColumns(3)
            ->recordActions([
                ViewAction::make()
                    ->label(''),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ItemAuditExporter::class)
                    ->label(__('item-audit.actions.export'))
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
            'index' => ManageItemAudits::route('/'),
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

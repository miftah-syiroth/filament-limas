<?php

namespace App\Filament\Resources\Maintenances;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Filament\Exports\MaintenanceExporter;
use App\Filament\Resources\Maintenances\Pages\ManageMaintenances;
use App\Models\Maintenance;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    public static function getModelLabel(): string
    {
        return __('maintenance.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('maintenance.plural_model_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('maintenance.navigation_label');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 3;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item.serial_number')
                    ->label(__('maintenance.infolist.item')),
                TextEntry::make('type')
                    ->label(__('maintenance.infolist.type'))
                    ->badge(),
                TextEntry::make('reported_at')
                    ->label(__('maintenance.infolist.reported_at'))
                    ->dateTime('d M Y')
                    ->placeholder('-'),
                TextEntry::make('started_at')
                    ->label(__('maintenance.infolist.started_at'))
                    ->dateTime('d M Y')
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->label(__('maintenance.infolist.completed_at'))
                    ->dateTime('d M Y')
                    ->placeholder('-'),
                TextEntry::make('cost')
                    ->label(__('maintenance.infolist.cost'))
                    ->money('IDR', decimalPlaces: 0)
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label(__('maintenance.infolist.status'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('itemAudit.code')
                    ->label(__('maintenance.infolist.audit_code'))
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->label(__('maintenance.infolist.notes'))
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('reported_at', direction: 'desc')
            ->columns([
                TextColumn::make('item.serial_number')
                    ->label(__('maintenance.table.item'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('maintenance.table.type'))
                    ->badge(),
                TextColumn::make('reported_at')
                    ->label(__('maintenance.table.reported_at'))
                    ->dateTime('d M Y'),
                TextColumn::make('started_at')
                    ->label(__('maintenance.table.started_at'))
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->label(__('maintenance.table.completed_at'))
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cost')
                    ->label(__('maintenance.table.cost'))
                    ->money('IDR', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('maintenance.table.status'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('itemAudit.code')
                    ->label(__('maintenance.table.audit'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('itemAudit', fn (Builder $q) => $q->where('id', 'ilike', "%{$search}%"));
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('maintenance.filters.status'))
                    ->multiple()
                    ->options(MaintenanceStatus::class),
                SelectFilter::make('type')
                    ->label(__('maintenance.filters.type'))
                    ->multiple()
                    ->options(MaintenanceType::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(MaintenanceExporter::class)
                    ->label(__('maintenance.actions.export'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->fileDisk('public'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMaintenances::route('/'),
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

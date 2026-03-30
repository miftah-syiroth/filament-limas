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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Maintenance';

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 3;

    // public static function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             Select::make('item_id')
    //                 ->relationship('item', 'name')
    //                 ->required(),
    //             Select::make('type')
    //                 ->options(MaintenanceType::class)
    //                 ->required(),
    //             DateTimePicker::make('reported_at'),
    //             DateTimePicker::make('started_at'),
    //             DateTimePicker::make('completed_at'),
    //             TextInput::make('cost')
    //                 ->numeric()
    //                 ->prefix('$'),
    //             Textarea::make('notes')
    //                 ->columnSpanFull(),
    //             Select::make('status')
    //                 ->options(MaintenanceStatus::class),
    //             Select::make('item_audit_id')
    //                 ->relationship('itemAudit', 'id'),
    //         ]);
    // }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item.serial_number')
                    ->label('Item'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('reported_at')
                    ->label('Laporan Masuk')
                    ->dateTime('d M Y')
                    ->placeholder('-'),
                TextEntry::make('started_at')
                    ->label('Mulai Maintenance')
                    ->dateTime('d M Y')
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->label('Selesai Maintenance')
                    ->dateTime('d M Y')
                    ->placeholder('-'),
                TextEntry::make('cost')
                    ->label('Biaya')
                    ->money('IDR', decimalPlaces: 0)
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('itemAudit.code')
                    ->label('Kode Audit')
                    ->placeholder('-'),
                TextEntry::make('notes')
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
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('reported_at')
                    ->label('Laporan')
                    ->dateTime('d M Y'),
                TextColumn::make('started_at')
                    ->label('Mulai')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->label('Selesai')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cost')
                    ->label('Biaya')
                    ->money('IDR', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('itemAudit.code')
                    ->label('Audit')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('itemAudit', fn (Builder $q) => $q->where('id', 'ilike', "%{$search}%"));
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(MaintenanceStatus::class),
                SelectFilter::make('type')
                    ->multiple()
                    ->options(MaintenanceType::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export')
                    ->exporter(MaintenanceExporter::class)
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

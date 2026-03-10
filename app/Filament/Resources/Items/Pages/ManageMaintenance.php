<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Filament\Resources\Items\ItemResource;
use App\Models\ItemAudit;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ManageMaintenance extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrench;

    protected static string $relationship = 'maintenances';

    protected static ?string $navigationLabel = 'Maintenance';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(MaintenanceType::class)
                    ->native(false)
                    ->required(),
                DatePicker::make('reported_at')
                    ->label('Tanggal Laporan')
                    ->required(),
                DatePicker::make('started_at')
                    ->label('Tanggal Mulai')
                    ->required(fn (Get $get): bool => MaintenanceStatus::tryFrom($get('status')) === MaintenanceStatus::Completed),
                DatePicker::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->required(fn (Get $get): bool => MaintenanceStatus::tryFrom($get('status')) === MaintenanceStatus::Completed),
                Select::make('item_audit_id')
                    ->label('Audit')
                    ->options(function (): array {
                        $ownerRecord = $this->getOwnerRecord();

                        return ItemAudit::query()
                            ->when($ownerRecord, fn (Builder $q) => $q->where('item_id', $ownerRecord->getKey()))
                            ->latest('audited_at')
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(fn (ItemAudit $audit) => [$audit->id => $audit->code])
                            ->all();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        $ownerRecord = $this->getOwnerRecord();

                        return ItemAudit::query()
                            ->when($ownerRecord, fn (Builder $q) => $q->where('item_id', $ownerRecord->getKey()))
                            ->where('id', 'like', "%{$search}%")
                            ->latest('audited_at')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn (ItemAudit $audit) => [$audit->id => $audit->code])
                            ->all();
                    })
                    ->getOptionLabelUsing(fn (?string $value): ?string => $value ? ItemAudit::find($value)?->code : null)
                    ->native(false),
                Select::make('status')
                    ->options(MaintenanceStatus::class)
                    ->native(false)
                    ->required()
                    ->live(),
                TextInput::make('cost')
                    ->label('Biaya')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp'),
                Textarea::make('notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('reported_at', direction: 'desc')
            ->columns([
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('itemAudit.code')
                    ->label('Kode Audit')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('itemAudit', fn (Builder $q) => $q->where('id', 'like', "%{$search}"));
                    }),
                TextColumn::make('reported_at')
                    ->label('Tanggal Laporan')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('started_at')
                    ->label('Tanggal Mulai')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('cost')
                    ->label('Biaya')
                    ->numeric()
                    ->prefix('Rp ')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->hiddenLabel(),
                DeleteAction::make()
                    ->hiddenLabel(),
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

<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Filament\Resources\Items\ItemResource;
use App\Models\ItemAudit;
use App\Models\ItemStateLog;
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
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class ManageMaintenance extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrench;

    protected static string $relationship = 'maintenances';

    public static function getNavigationLabel(): string
    {
        return __('items.pages.maintenance.navigation_label');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(MaintenanceType::class)
                    ->native(false)
                    ->required(),
                DatePicker::make('reported_at')
                    ->label(__('items.pages.maintenance.reported_at'))
                    ->required(),
                DatePicker::make('started_at')
                    ->label(__('items.pages.maintenance.started_at'))
                    ->required(fn (Get $get): bool => $get('status') === MaintenanceStatus::Completed)
                    ->afterOrEqual('reported_at'),
                DatePicker::make('completed_at')
                    ->label(__('items.pages.maintenance.completed_at'))
                    ->required(fn (Get $get): bool => $get('status') === MaintenanceStatus::Completed)
                    ->afterOrEqual('started_at'),
                Select::make('item_audit_id')
                    ->label(__('items.pages.maintenance.audit'))
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
                            ->where('id', 'ilike', "%{$search}%")
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
                    ->label(__('items.pages.maintenance.cost'))
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp'),
                Textarea::make('notes'),
                Fieldset::make(__('items.pages.maintenance.fieldset_item_status'))
                    // sembunyikan berdasarkan authorization create dan status item (Active, UnderDiagnosis, UnderRepair, Damaged)
                    ->visible(Gate::allows('create', $this->getOwnerRecord()))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('from_status')
                            ->label(__('items.pages.maintenance.status_from'))
                            ->options(ItemStatus::class)
                            ->default(fn (): ?string => $this->getOwnerRecord()?->status?->value)
                            ->disabled(),
                        Select::make('to_status')
                            ->label(__('items.pages.maintenance.status_to'))
                            ->options(ItemStatus::class)
                            ->native(false),
                    ]),
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
                    ->label(__('items.pages.maintenance.audit_code'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('itemAudit', fn (Builder $q) => $q->where('id', 'ilike', "%{$search}%"));
                    }),
                TextColumn::make('reported_at')
                    ->label(__('items.pages.maintenance.reported_at'))
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('started_at')
                    ->label(__('items.pages.maintenance.started_at'))
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label(__('items.pages.maintenance.completed_at'))
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('cost')
                    ->label(__('items.pages.maintenance.cost'))
                    ->numeric()
                    ->prefix('Rp ')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->authorize('create', $this->getOwnerRecord())
                    ->label(__('items.pages.maintenance.add'))
                    ->after(function (array $data): void {
                        if (filled($data['to_status'] ?? null)) {
                            ItemStateLog::create([
                                'item_id' => $this->getOwnerRecord()->id,
                                'maintenance_id' => $this->getOwnerRecord()->latestMaintenance->id,
                                'event_type' => ItemStateEventType::StatusChange,
                                'from_status' => $this->getOwnerRecord()->status,
                                'to_status' => $data['to_status'],
                                'notes' => $data['notes'] ?? null,
                            ]);
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->hiddenLabel()
                    ->closeModalByClickingAway(false)
                    ->label(__('items.pages.maintenance.edit'))
                    ->fillForm(fn ($record): array => [
                        ...$record->toArray(),
                        'from_status' => $this->getOwnerRecord()?->status?->value,
                    ])
                    ->after(function (array $data): void {
                        if (filled($data['to_status'] ?? null)) {
                            ItemStateLog::create([
                                'item_id' => $this->getOwnerRecord()->id,
                                'maintenance_id' => $this->getOwnerRecord()->latestMaintenance->id,
                                'event_type' => ItemStateEventType::StatusChange,
                                'from_status' => $this->getOwnerRecord()->status,
                                'to_status' => $data['to_status'],
                                'notes' => $data['notes'] ?? null,
                            ]);
                        }
                    }),
                DeleteAction::make()
                    ->hiddenLabel(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn (Collection $records) => $records->each->delete()),
                    ForceDeleteBulkAction::make()
                        ->authorizeIndividualRecords('forceDelete')
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                    RestoreBulkAction::make()
                        ->authorizeIndividualRecords('restore')
                        ->action(fn (Collection $records) => $records->each->restore()),
                ]),
            ]);
    }
}

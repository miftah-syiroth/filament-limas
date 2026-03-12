<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use App\Filament\Resources\Items\ItemResource;
use App\Models\ItemStateLog;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ManageItemAudits extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    protected static string $relationship = 'audits';

    protected static ?string $navigationLabel = 'Audit';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('audited_at')
                    ->label('Tanggal Audit')
                    ->required()
                    ->default(now()->format('m/d/Y')),
                Select::make('condition')
                    ->options(ItemAuditCondition::class)
                    ->native(false)
                    ->required(),
                Select::make('result')
                    ->options(ItemAuditResult::class)
                    ->native(false)
                    ->required(),
                Toggle::make('location_verified')
                    ->label('Lokasi Diverifikasi')
                    ->inline(false),
                // jika item->model->audit_interval null, maka input next audit date
                DatePicker::make('next_audit_at')
                    ->label('Tanggal Audit Berikutnya')
                    ->required()
                    ->default(function (): Carbon {
                        $auditInterval = $this->getOwnerRecord()?->model?->audit_interval ?? 3;

                        return Carbon::now()->addMonths($auditInterval);
                    }),
                Textarea::make('notes'),
                Fieldset::make('Status Item')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('from_status')
                            ->label('Status dari')
                            ->options(ItemStatus::class)
                            ->default(fn (): ?string => $this->getOwnerRecord()?->status?->value)
                            ->disabled()
                            ->dehydrated(),
                        Select::make('to_status')
                            ->label('Status ke')
                            ->options(ItemStatus::class)
                            ->native(false),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('audited_at', direction: 'desc')
            ->columns([
                TextColumn::make('audited_at')
                    ->label('Tanggal Audit')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('condition'),
                TextColumn::make('result'),
                IconColumn::make('location_verified')
                    ->label('Lokasi Diverifikasi')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('next_audit_at')
                    ->label('Audit Berikutnya')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('id', 'ilike', "%{$search}%");
                    }),
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->authorize('create', $this->getOwnerRecord())
                    ->label('Tambah Audit')
                    ->after(function (array $data): void {
                        if (filled($data['to_status'] ?? null)) {
                            ItemStateLog::create([
                                'item_id' => $this->getOwnerRecord()->id,
                                'item_audit_id' => $this->getOwnerRecord()->latestAudit->id,
                                'event_type' => ItemStateEventType::StatusChange,
                                'from_status' => $this->getOwnerRecord()->status,
                                'to_status' => $data['to_status'],
                                'notes' => $data['notes'] ?? null,
                            ]);
                        }
                    }),
            ]);
        // ->recordActions([
        //     DeleteAction::make(),
        // ])
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DeleteBulkAction::make(),
        //         ForceDeleteBulkAction::make(),
        //         RestoreBulkAction::make(),
        //     ]),
        // ]);
    }
}

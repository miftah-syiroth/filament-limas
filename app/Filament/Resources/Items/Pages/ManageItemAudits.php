<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use App\Filament\Resources\Items\ItemResource;
use App\Models\ItemStateLog;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class ManageItemAudits extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    protected static string $relationship = 'audits';

    // public static function getRelationshipTitle(): string
    // {
    //     return __('items.pages.audits.relationship_title');
    // }

    public function getTitle(): string | Htmlable
    {
        $item = $this->getOwnerRecord();
        return __('items.pages.audits.title', [
            'item' => $item->serial_number ?? $item->name,
        ]);
    }

    public static function getNavigationLabel(): string
    {
        return __('items.pages.audits.navigation_label');
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('audited_at')
                    ->label(__('items.pages.audits.audited_at'))
                    ->required()
                    ->default(now()->format('m/d/Y'))
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state): void {
                        $auditInterval = $this->getOwnerRecord()?->model?->audit_interval ?? 3;
                        // jika state adalah null, maka set next audit date ke null
                        if (blank($state)) {
                            $set('next_audit_at', Carbon::now()->addMonths($auditInterval));
                            return;
                        }

                        $set('next_audit_at', Carbon::parse($state)->addMonths($auditInterval));
                    }),
                Select::make('condition')
                    ->options(ItemAuditCondition::class)
                    ->native(false)
                    ->required(),
                Select::make('result')
                    ->options(ItemAuditResult::class)
                    ->native(false)
                    ->required(),
                Toggle::make('location_verified')
                    ->label(__('items.pages.audits.location_verified'))
                    ->inline(false),
                // jika item->model->audit_interval null, maka input next audit date
                DatePicker::make('next_audit_at')
                    ->label(__('items.pages.audits.next_audit_at'))
                    ->required()
                    ->default(function (): Carbon {
                        $auditInterval = $this->getOwnerRecord()?->model?->audit_interval ?? 3;

                        return Carbon::now()->addMonths($auditInterval);
                    }),
                Textarea::make('notes'),
                Fieldset::make(__('items.pages.audits.fieldset_item_status'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('from_status')
                            ->label(__('items.pages.audits.status_from'))
                            ->options(ItemStatus::class)
                            ->default(fn(): ?string => $this->getOwnerRecord()?->status?->value)
                            ->disabled()
                            ->dehydrated(),
                        Select::make('to_status')
                            ->label(__('items.pages.audits.status_to'))
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
                    ->label(__('items.pages.audits.audited_at'))
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('condition'),
                TextColumn::make('result'),
                IconColumn::make('location_verified')
                    ->label(__('items.pages.audits.location_verified'))
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('next_audit_at')
                    ->label(__('items.pages.audits.next_audit'))
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('code')
                    ->label(__('items.pages.audits.code'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('id', 'ilike', "%{$search}%");
                    }),
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('items.table.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make()->hiddenLabel(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->authorize('create', $this->getOwnerRecord())
                    ->label(__('items.pages.audits.add'))
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

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn(Collection $records) => $records->each->delete()),
                    RestoreBulkAction::make()
                        ->authorizeIndividualRecords('restore')
                        ->action(fn(Collection $records) => $records->each->restore()),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use App\Filament\Resources\Items\ItemResource;
use App\Models\ItemStateLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ManageItemStateLogs extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string $relationship = 'stateLogs';

    public static function getNavigationLabel(): string
    {
        return __('items.pages.state_logs.navigation_label');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_type')
                    ->label(__('items.pages.state_logs.event_type'))
                    ->options(ItemStateEventType::class)
                    ->required()
                    ->native(false)
                    ->live()
                    ->columnSpanFull(),
                Select::make('from_location_id')
                    ->label(__('items.pages.state_logs.location_from'))
                    ->relationship('fromLocation', 'name')
                    ->dehydrated()
                    ->default(fn (): ?string => $this->getOwnerRecord()->location_id)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->disabled(),
                Select::make('to_location_id')
                    ->label(__('items.pages.state_logs.location_to'))
                    ->relationship(
                        name: 'toLocation',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query): Builder {
                            return $query->where('id', '!=', $this->getOwnerRecord()->location_id);
                        },
                    )
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('to_department_id', null);
                        $set('to_room_id', null);
                    })
                    ->native(false)->requiredWithoutAll('to_department_id,to_room_id'),
                Select::make('from_department_id')
                    ->label(__('items.pages.state_logs.department_from'))
                    ->relationship(
                        name: 'fromDepartment',
                        titleAttribute: 'name'
                    )
                    ->dehydrated()
                    ->default(fn (): ?string => $this->getOwnerRecord()->department_id)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->disabled(),
                Select::make('to_department_id')
                    ->label(__('items.pages.state_logs.department_to'))
                    ->relationship(
                        name: 'toDepartment',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query, Get $get): Builder {
                            return $query->when(
                                $locationId = $get('to_location_id') ?? $this->getOwnerRecord()->location_id,
                                fn (Builder $q): Builder => $q->where('location_id', $locationId)
                            )->when(
                                $departmentId = $this->getOwnerRecord()->department_id,
                                fn (Builder $q): Builder => $q->where('id', '!=', $departmentId)
                            );
                        },
                    )
                    ->searchable()
                    ->preload()
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->required(function (Get $get): bool {
                        return $get('to_location_id') !== null;
                    })->requiredWithoutAll('to_location_id,to_room_id'),
                Select::make('from_room_id')
                    ->label(__('items.pages.state_logs.room_from'))
                    ->relationship(
                        name: 'fromRoom',
                        titleAttribute: 'name'
                    )
                    ->dehydrated()
                    ->default(fn (): ?string => $this->getOwnerRecord()->room_id)
                    ->disabled()
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value),
                Select::make('to_room_id')
                    ->label(__('items.pages.state_logs.room_to'))
                    ->relationship(
                        name: 'toRoom',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query, Get $get): Builder {
                            return $query->when(
                                $locationId = $get('to_location_id') ?? $this->getOwnerRecord()->location_id,
                                fn (Builder $q): Builder => $q->where('location_id', $locationId)
                            )->when(
                                $roomId = $this->getOwnerRecord()->room_id,
                                fn (Builder $q): Builder => $q->where('id', '!=', $roomId)
                            );
                        },
                    )
                    ->searchable()
                    ->preload()
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->required(function (Get $get): bool {
                        return $get('to_location_id') !== null;
                    })->requiredWithoutAll('to_location_id,to_department_id'),
                Select::make('from_user_id')
                    ->label(__('items.pages.state_logs.user_from'))
                    ->relationship('fromUser', 'name')
                    ->default(fn (): ?string => $this->getOwnerRecord()->user_id)
                    ->dehydrated()
                    ->disabled()
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Assignment->value),
                Select::make('to_user_id')
                    ->label(__('items.pages.state_logs.user_to'))
                    ->relationship(
                        name: 'toUser',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query): Builder {
                            return $query->when(
                                $userId = $this->getOwnerRecord()->user_id,
                                fn (Builder $q): Builder => $q->where('id', '!=', $userId)
                            );
                        },
                    )
                    ->searchable()
                    ->preload()
                    ->required(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Assignment->value)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Assignment->value),
                Select::make('from_status')
                    ->label(__('items.pages.state_logs.status_from'))
                    ->options(ItemStatus::class)
                    ->default(fn (): ?string => $this->getOwnerRecord()->status->value)
                    ->dehydrated()
                    ->disabled()
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::StatusChange->value),
                Select::make('to_status')
                    ->label(__('items.pages.state_logs.status_to'))
                    ->options(function (): array {
                        $currentStatus = $this->getOwnerRecord()->status;

                        return collect(ItemStatus::cases())
                            ->reject(fn (ItemStatus $status): bool => $status === $currentStatus)
                            ->mapWithKeys(fn (ItemStatus $status): array => [$status->value => $status->getLabel()])
                            ->all();
                    })
                    ->native(false)
                    ->required(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::StatusChange->value)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::StatusChange->value),
                Textarea::make('notes')
                    ->label(__('items.pages.state_logs.notes')),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function nullifyFromWhenToIsNull(array $data): array
    {
        $eventType = $data['event_type'] ?? null;
        $eventValue = $eventType instanceof ItemStateEventType ? $eventType->value : $eventType;

        if ($eventValue === ItemStateEventType::Transfer->value) {
            if (empty($data['to_location_id'])) {
                $data['from_location_id'] = null;
                $data['from_department_id'] = null;
            }
            if (empty($data['to_department_id'])) {
                $data['from_department_id'] = null;
            }

            if (empty($data['to_room_id'])) {
                $data['from_room_id'] = null;
            }
        }

        if ($eventValue === ItemStateEventType::Assignment->value && empty($data['to_user_id'])) {
            $data['from_user_id'] = null;
        }

        if ($eventValue === ItemStateEventType::StatusChange->value && empty($data['to_status'])) {
            $data['from_status'] = null;
        }

        return $data;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', direction: 'desc')
            ->columns([
                TextColumn::make('event_type')
                    ->label(__('items.pages.state_logs.type_short')),
                TextColumn::make('fromLocation.name')
                    ->label(__('items.pages.state_logs.location_from'))
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('toLocation.name')
                    ->label(__('items.pages.state_logs.location_to'))
                    ->color('primary'),
                TextColumn::make('fromDepartment.name')
                    ->label(__('items.pages.state_logs.department_from'))
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('toDepartment.name')
                    ->label(__('items.pages.state_logs.department_to'))
                    ->color('primary'),
                TextColumn::make('fromRoom.name')
                    ->label(__('items.pages.state_logs.room_from'))
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('toRoom.name')
                    ->label(__('items.pages.state_logs.room_to'))
                    ->color('primary'),
                TextColumn::make('fromUser.name')
                    ->label(__('items.pages.state_logs.responsible_from'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('toUser.name')
                    ->label(__('items.pages.state_logs.responsible_to'))
                    ->badge()
                    ->color('primary'),
                TextColumn::make('from_status')
                    ->label(__('items.pages.state_logs.status_from'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('to_status')
                    ->label(__('items.pages.state_logs.status_to'))
                    ->badge()
                    ->color('primary'),
                TextColumn::make('created_at')
                    ->dateTime('j M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->label(__('items.pages.state_logs.add_transfer'))
                    ->closeModalByClickingAway(false)
                    ->mutateDataUsing(fn (array $data): array => $this->nullifyFromWhenToIsNull($data)),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (ItemStateLog $record): string => __('items.pages.state_logs.modal_heading', [
                        'type' => $record->event_type->getLabel(),
                    ]))
                    ->modalSubmitAction(false)
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('event_type')
                                ->label(__('items.pages.state_logs.event_type'))
                                ->badge(),
                            TextEntry::make('created_at')
                                ->label(__('items.pages.state_logs.created_at'))
                                ->dateTime('j M Y H:i'),
                            TextEntry::make('fromLocation.name')
                                ->label(__('items.pages.state_logs.location_from'))
                                ->placeholder('—'),
                            TextEntry::make('toLocation.name')
                                ->label(__('items.pages.state_logs.location_to'))
                                ->placeholder('—'),
                            TextEntry::make('fromDepartment.name')
                                ->label(__('items.pages.state_logs.department_from'))
                                ->placeholder('—'),
                            TextEntry::make('toDepartment.name')
                                ->label(__('items.pages.state_logs.department_to'))
                                ->placeholder('—'),
                            TextEntry::make('fromRoom.name')
                                ->label(__('items.pages.state_logs.room_from'))
                                ->placeholder('—'),
                            TextEntry::make('toRoom.name')
                                ->label(__('items.pages.state_logs.room_to'))
                                ->placeholder('—'),
                            TextEntry::make('fromUser.name')
                                ->label(__('items.pages.state_logs.user_from'))
                                ->placeholder('—'),
                            TextEntry::make('toUser.name')
                                ->label(__('items.pages.state_logs.user_to'))
                                ->placeholder('—'),
                            TextEntry::make('from_status')
                                ->label(__('items.pages.state_logs.status_from'))
                                ->badge()
                                ->placeholder('—'),
                            TextEntry::make('to_status')
                                ->label(__('items.pages.state_logs.status_to'))
                                ->badge()
                                ->placeholder('—'),
                            TextEntry::make('notes')
                                ->label(__('items.pages.state_logs.notes'))
                                ->placeholder('—'),

                        ]),
                    ]),
            ]);
    }
}

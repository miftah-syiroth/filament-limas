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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ManageItemStateLogs extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string $relationship = 'stateLogs';

    // nama menu
    protected static ?string $navigationLabel = 'Transfer & Status';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_type')
                    ->label('Tipe Event')
                    ->options(ItemStateEventType::class)
                    ->required()
                    ->native(false)
                    ->live()
                    ->columnSpanFull(),
                Select::make('from_location_id')
                    ->label('Lokasi dari')
                    ->relationship('fromLocation', 'name')
                    ->default(fn (): ?string => $this->getOwnerRecord()?->location_id)
                    ->disabled()
                    ->dehydrated()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->live(),
                Select::make('to_location_id')
                    ->label('Lokasi ke')
                    ->relationship('toLocation', 'name')
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->live()
                    ->required(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->afterStateUpdated(fn (Select $component) => $component
                        ->getContainer()
                        ->getComponent('to_department_id')
                        ->state(null))
                    ->native(false),
                Select::make('from_department_id')
                    ->label('Departemen dari')
                    ->relationship(
                        name: 'fromDepartment',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query->when(
                            $get('from_location_id'),
                            fn (Builder $q): Builder => $q->where('location_id', $get('from_location_id')),
                        ),
                    )
                    ->default(fn (): ?string => $this->getOwnerRecord()?->department_id)
                    ->disabled()
                    ->dehydrated()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value),
                Select::make('to_department_id')
                    ->label('Departemen ke')
                    ->relationship(
                        name: 'toDepartment',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query->when(
                            $get('to_location_id'),
                            fn (Builder $q): Builder => $q->where('location_id', $get('to_location_id')),
                            fn (Builder $q): Builder => $q->whereRaw('1 = 0'),
                        ),
                    )
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Transfer->value),
                Select::make('from_user_id')
                    ->label('Pengguna dari')
                    ->relationship('fromUser', 'name')
                    ->default(fn (): ?string => $this->getOwnerRecord()?->user_id)
                    ->disabled()
                    ->dehydrated()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Assignment->value),
                Select::make('to_user_id')
                    ->label('Pengguna ke')
                    ->relationship('toUser', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Assignment->value)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::Assignment->value),
                Select::make('from_status')
                    ->label('Status dari')
                    ->options(ItemStatus::class)
                    ->default(fn (): ?string => $this->getOwnerRecord()?->status?->value)
                    ->disabled()
                    ->dehydrated()
                    ->native(false)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::StatusChange->value),
                Select::make('to_status')
                    ->label('Status ke')
                    ->options(ItemStatus::class)
                    ->native(false)
                    ->visible(fn (Get $get): bool => ($get('event_type')?->value ?? $get('event_type')) === ItemStateEventType::StatusChange->value),
                Textarea::make('notes')
                    ->label('Catatan'),
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
                    ->label('Tipe'),
                TextColumn::make('fromLocation.name')
                    ->label('Lokasi dari')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('toLocation.name')
                    ->label('Lokasi ke')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('fromDepartment.name')
                    ->label('Departemen dari')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('toDepartment.name')
                    ->label('Departemen ke')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('fromUser.name')
                    ->label('PJ dari')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('toUser.name')
                    ->label('PJ ke')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('from_status')
                    ->label('Status dari')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('to_status')
                    ->label('Status ke')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('j M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('j M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Transfer')
                    ->closeModalByClickingAway(false)
                    ->mutateDataUsing(fn (array $data): array => $this->nullifyFromWhenToIsNull($data)),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (ItemStateLog $record): string => 'Detail Log - '.$record->event_type->getLabel())
                    ->modalSubmitAction(false)
                    ->schema([
                        Section::make('Informasi Transfer & Status')
                            ->schema([
                                TextEntry::make('event_type')
                                    ->label('Tipe Event')
                                    ->badge(),
                                TextEntry::make('fromLocation.name')
                                    ->label('Lokasi dari')
                                    ->placeholder('—'),
                                TextEntry::make('toLocation.name')
                                    ->label('Lokasi ke')
                                    ->placeholder('—'),
                                TextEntry::make('fromDepartment.name')
                                    ->label('Departemen dari')
                                    ->placeholder('—'),
                                TextEntry::make('toDepartment.name')
                                    ->label('Departemen ke')
                                    ->placeholder('—'),
                                TextEntry::make('fromUser.name')
                                    ->label('Pengguna dari')
                                    ->placeholder('—'),
                                TextEntry::make('toUser.name')
                                    ->label('Pengguna ke')
                                    ->placeholder('—'),
                                TextEntry::make('from_status')
                                    ->label('Status dari')
                                    ->badge()
                                    ->placeholder('—'),
                                TextEntry::make('to_status')
                                    ->label('Status ke')
                                    ->badge()
                                    ->placeholder('—'),
                                TextEntry::make('notes')
                                    ->label('Catatan')
                                    ->placeholder('—'),
                                TextEntry::make('created_at')
                                    ->label('Dibuat')
                                    ->dateTime('j M Y H:i'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Borrowings\RelationManagers;

use App\Enums\ItemAuditCondition;
use App\Filament\Infolists\Components\QrCodeEntry;
use App\Models\BorrowingItem;
use App\Models\Item;
use Filament\Actions\Action;
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
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->label('Item')
                    ->searchable()
                    // option limit 20
                    ->options(Item::query()
                        ->limit(20)
                        ->get()
                        ->mapWithKeys(fn (Item $item): array => [
                            $item->id => "{$item->serial_number} - {$item->model?->name}",
                        ])
                        ->all())
                    ->getSearchResultsUsing(fn (string $search): array => Item::query()
                        ->where('serial_number', 'like', "{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->limit(20)
                        ->get()
                        ->mapWithKeys(fn (Item $item): array => [
                            $item->id => "{$item->serial_number} - {$item->model?->name}",
                        ])
                        ->all())
                    ->getOptionLabelUsing(fn ($value): ?string => Item::find($value)?->serial_number
                        ? Item::find($value)?->serial_number.' - '.Item::find($value)?->model?->name
                        : null)
                    ->required()
                    ->live()
                    ->preload()
                    ->native(false)
                    ->afterStateUpdated(function (Set $set, $state): void {
                        $set('quantity', null);
                        $set('condition_out', null);
                        if ($state) {
                            $item = Item::with('latestAudit')->find($state);
                            $set('quantity', $item?->quantity ?? 1);
                            $set('condition_out', $item?->latestAudit?->condition?->value);
                        }
                    }),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->default(1)
                    ->live()
                    ->maxValue(fn (Get $get): ?int => Item::find($get('item_id'))?->quantity),
                DatePicker::make('checked_out_at')
                    ->label('Tanggal Keluar')
                    ->default(now()->format('m/d/Y'))
                    ->required(),
                Select::make('condition_out')
                    ->label('Kondisi Keluar')
                    ->options(ItemAuditCondition::class)
                    ->native(false)
                    ->required(),
                DatePicker::make('checked_in_at')
                    ->label('Tanggal Masuk'),
                Select::make('condition_in')
                    ->label('Kondisi Masuk')
                    ->options(ItemAuditCondition::class)
                    ->native(false),
                Textarea::make('notes')
                    ->label('Catatan'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Items')
            ->columns([
                TextColumn::make('item.serial_number')
                    ->label('Serial Number'),
                TextColumn::make('item.model.name')
                    ->label('Model'),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('checked_out_at')
                    ->label('Tanggal Keluar')
                    ->dateTime('j M Y'),
                TextColumn::make('condition_out')
                    ->label('Kondisi Keluar')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('checked_in_at')
                    ->label('Tanggal Masuk')
                    ->dateTime('j M Y'),
                TextColumn::make('condition_in')
                    ->label('Kondisi Masuk')
                    ->badge()
                    ->color('primary'),
                // notes,
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Item'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->modalSubmitAction(false)
                    ->modalWidth('md')
                    ->schema([
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        QrCodeEntry::make('qr_code')
                                            ->state(fn (BorrowingItem $record): string => $record->item->serial_number)
                                            ->hiddenLabel(),
                                    ])
                                    ->columnSpan(1),
                                Grid::make(1)
                                    ->schema([
                                        TextEntry::make('item.serial_number')
                                            ->label('Serial Number')
                                            ->badge(),
                                        TextEntry::make('quantity')
                                            ->label('Jumlah')
                                            ->numeric(),
                                    ])
                                    ->columnSpan(1),
                            ]),
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                Fieldset::make('Keluar')
                                    ->columns(1)
                                    ->schema([
                                        TextEntry::make('checked_out_at')
                                            ->label('Tanggal')
                                            ->dateTime('j M Y'),
                                        TextEntry::make('condition_out')
                                            ->hiddenLabel()
                                            ->badge()
                                            ->color('gray'),
                                    ]),
                                Fieldset::make('Masuk')
                                    ->schema([
                                        TextEntry::make('checked_in_at')
                                            ->label('Tanggal')
                                            ->dateTime('j M Y'),
                                        TextEntry::make('condition_in')
                                            ->hiddenLabel()
                                            ->badge()
                                            ->color('primary'),
                                    ]),
                            ]),
                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->placeholder('-'),
                    ]),
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

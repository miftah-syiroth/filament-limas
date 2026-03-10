<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use App\Enums\ItemAuditCondition;
use App\Models\Item;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class BorrowingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Peminjam')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Peminjam')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                        DatePicker::make('borrowed_at')
                            ->label('Tanggal Peminjaman')
                            ->required()
                            ->default(now()->format('m/d/Y')),
                        DatePicker::make('due_at')
                            ->label('Batas Peminjaman')
                            ->required(),
                        DatePicker::make('returned_at')
                            ->label('Tanggal Pengembalian'),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
                Section::make('Items')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->columns(3)
                            ->schema([
                                Select::make('item_id')
                                    ->label('Item')
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (string $search): array => Item::query()
                                        ->where('serial_number', 'like', "{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%")
                                        ->limit(20)
                                        ->get()
                                        ->mapWithKeys(fn (Item $item): array => [
                                            $item->id => "{$item->serial_number} - {$item->quantity}",
                                        ])
                                        ->all())
                                    ->getOptionLabelUsing(fn ($value): ?string => Item::find($value)?->serial_number
                                        ? Item::find($value)?->serial_number.' - '.Item::find($value)?->quantity
                                        : null)
                                    ->required()
                                    ->live()
                                    ->native(false)
                                    ->afterStateUpdated(function (Set $set, $state): void {
                                        $set('quantity', null);
                                        $set('condition_in', null);
                                        if ($state) {
                                            $item = Item::with('latestAudit')->find($state);
                                            $set('quantity', $item?->quantity ?? 1);
                                            $set('condition_in', $item?->latestAudit?->condition?->value);
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
                                Select::make('condition_in')
                                    ->label('Kondisi Masuk')
                                    ->options(ItemAuditCondition::class)
                                    ->native(false)
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel('Tambah Item'),
                    ]),
            ]);
    }
}

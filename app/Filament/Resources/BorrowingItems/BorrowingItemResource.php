<?php

namespace App\Filament\Resources\BorrowingItems;

use App\Enums\ItemAuditCondition;
use App\Filament\Resources\BorrowingItems\Pages\ManageBorrowingItems;
use App\Models\BorrowingItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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

class BorrowingItemResource extends Resource
{
    protected static ?string $model = BorrowingItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Peminjaman';

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    // public static function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             Select::make('borrowing_id')
    //                 ->relationship('borrowing', 'id')
    //                 ->required(),
    //             Select::make('item_id')
    //                 ->relationship('item', 'name')
    //                 ->required(),
    //             TextInput::make('quantity')
    //                 ->required()
    //                 ->numeric(),
    //             DateTimePicker::make('checked_out_at')
    //                 ->required(),
    //             DateTimePicker::make('checked_in_at'),
    //             Select::make('condition_in')
    //                 ->options(ItemAuditCondition::class),
    //             Select::make('condition_out')
    //                 ->options(ItemAuditCondition::class)
    //                 ->required(),
    //             Textarea::make('notes')
    //                 ->columnSpanFull(),
    //         ]);
    // }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('item.serial_number')
                    ->label('Serial Number'),
                TextEntry::make('borrowing.id')
                    ->label('Borrowing'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('checked_out_at')
                    ->dateTime(),
                TextEntry::make('checked_in_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('condition_in')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('condition_out')
                    ->badge(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(BorrowingItem $record): bool => $record->trashed()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('item.serial_number')
                    ->label('Serial Number')
                    ->searchable(),
                TextColumn::make('item.model.name')
                    ->label('Model'),
                TextColumn::make('borrowing.user.name')
                    ->label('Peminjam')
                    ->searchable(),
                TextColumn::make('borrowing.user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('checked_out_at')
                    ->label('Waktu Keluar')
                    ->dateTime('j M Y'),
                TextColumn::make('condition_out')
                    ->label('Kondisi Keluar')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('checked_in_at')
                    ->label('Waktu Kembali')
                    ->dateTime('j M Y'),
                TextColumn::make('condition_in')
                    ->label('Kondisi Masuk')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('condition_in')
                    ->label('Kondisi Masuk')
                    ->multiple()
                    ->options(ItemAuditCondition::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
                // EditAction::make(),
                // DeleteAction::make(),
                // ForceDeleteAction::make(),
                // RestoreAction::make(),
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
            'index' => ManageBorrowingItems::route('/'),
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

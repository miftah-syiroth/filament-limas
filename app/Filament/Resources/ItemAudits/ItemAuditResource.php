<?php

namespace App\Filament\Resources\ItemAudits;

use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use App\Filament\Resources\ItemAudits\Pages\ManageItemAudits;
use App\Models\ItemAudit;
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
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ItemAuditResource extends Resource
{
    protected static ?string $model = ItemAudit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Audit';

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    // public static function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             Select::make('item_id')
    //                 ->relationship('item', 'name')
    //                 ->required(),
    //             TextInput::make('status'),
    //             Toggle::make('location_verified')
    //                 ->required(),
    //             Textarea::make('notes')
    //                 ->columnSpanFull(),
    //             DateTimePicker::make('audited_at')
    //                 ->required(),
    //             DateTimePicker::make('next_audit_at'),
    //             Select::make('condition')
    //                 ->options(ItemAuditCondition::class),
    //             Select::make('result')
    //                 ->options(ItemAuditResult::class),
    //         ]);
    // }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('item.name')
                    ->label('Item'),
                TextEntry::make('status')
                    ->placeholder('-'),
                IconEntry::make('location_verified')
                    ->boolean(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('audited_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(ItemAudit $record): bool => $record->trashed()),
                TextEntry::make('next_audit_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('condition')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('result')
                    ->badge()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // penting: hilangkan clickable row (tidak ada URL)
            ->recordTitleAttribute('id')
            ->defaultSort('audited_at', direction: 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('item.serial_number')
                    ->searchable(),
                TextColumn::make('audited_at')
                    ->label('Tanggal Audit')
                    ->dateTime('d M Y')
                    ->sortable(),
                TextColumn::make('next_audit_at')
                    ->label('Audit Berikutnya')
                    ->dateTime('d M Y')
                    ->sortable(),
                IconColumn::make('location_verified')
                    ->label('Lokasi Sesuai')
                    ->boolean(),
                TextColumn::make('condition')
                    ->badge(),
                TextColumn::make('result')
                    ->badge(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'), // opsional: beri label supaya jelas
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Tidak perlu bulk actions untuk sekarang
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageItemAudits::route('/'),
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

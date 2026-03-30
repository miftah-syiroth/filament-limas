<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ManageActivityLogs;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'log_name';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('log_name'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('subject_type'),
                TextInput::make('subject_id'),
                TextInput::make('causer_type'),
                TextInput::make('causer_id'),
                TextInput::make('properties'),
                TextInput::make('event'),
                TextInput::make('batch_uuid'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('log_name')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('subject_type')
                    ->placeholder('-'),
                TextEntry::make('subject_id')
                    ->placeholder('-'),
                TextEntry::make('causer_type')
                    ->placeholder('-'),
                TextEntry::make('causer_id')
                    ->placeholder('-'),
                TextEntry::make('event')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            // jangen clickable
            ->columns([
                TextColumn::make('causer.name')
                ->label('Nama'),
                TextColumn::make('causer.email')
                ->label('Email'),
                TextColumn::make('event'),
                TextColumn::make('subject_type')
                    ->label('Tabel'),
                IconColumn::make('subject')
                    ->label('Row')
                    ->icon(fn (mixed $state): ?Heroicon => $state === null
                        ? null
                        : Heroicon::OutlinedEye)
                    ->color(fn (mixed $state): ?string => $state === null
                        ? null
                        : 'info')
                    ->url(
                        fn (ActivityLog $record): string => route('admin.activity-logs.show', ['activityLog' => $record, 'data' => 'subject']),
                    )
                    ->openUrlInNewTab(),
                IconColumn::make('properties')
                    ->label('Properties')
                    ->icon(fn (mixed $state): ?Heroicon => $state === null
                        ? null
                        : Heroicon::OutlinedEye)
                    ->color(fn (mixed $state): ?string => $state === null
                        ? null
                        : 'info')
                    ->url(
                        fn (ActivityLog $record): string => route('admin.activity-logs.show', ['activityLog' => $record, 'data' => 'properties']),
                    )
                    ->openUrlInNewTab(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageActivityLogs::route('/'),
        ];
    }
}

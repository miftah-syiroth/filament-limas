<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ManageActivityLogs;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Novadaemon\FilamentPrettyJson\Infolist\PrettyJsonEntry;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    public static function getModelLabel(): string
    {
        return __('activitylog.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('activitylog.plural_model_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('activitylog.navigation_label');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'log_name';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 10;

    // public static function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('log_name')
    //                 ->label(__('activitylog.form.log_name')),
    //             Textarea::make('description')
    //                 ->label(__('activitylog.form.description'))
    //                 ->required()
    //                 ->columnSpanFull(),
    //             TextInput::make('subject_type')
    //                 ->label(__('activitylog.form.subject_type')),
    //             TextInput::make('subject_id')
    //                 ->label(__('activitylog.form.subject_id')),
    //             TextInput::make('causer_type')
    //                 ->label(__('activitylog.form.causer_type')),
    //             TextInput::make('causer_id')
    //                 ->label(__('activitylog.form.causer_id')),
    //             TextInput::make('properties')
    //                 ->label(__('activitylog.form.properties')),
    //             TextInput::make('event')
    //                 ->label(__('activitylog.form.event')),
    //             TextInput::make('batch_uuid')
    //                 ->label(__('activitylog.form.batch_uuid')),
    //         ]);
    // }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('event')
                    ->label(__('activitylog.infolist.event'))
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label(__('activitylog.infolist.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('subject_type')
                    ->label(__('activitylog.infolist.subject_type'))
                    ->placeholder('-'),
                TextEntry::make('subject_id')
                    ->label(__('activitylog.infolist.subject_id'))
                    ->placeholder('-'),
                TextEntry::make('causer_type')
                    ->label(__('activitylog.infolist.causer_type'))
                    ->placeholder('-'),
                TextEntry::make('causer_id')
                    ->label(__('activitylog.infolist.causer_id'))
                    ->placeholder('-'),
                PrettyJsonEntry::make('subject')
                    ->label(__('activitylog.table.subject_record')),
                PrettyJsonEntry::make('properties')
                    ->label(__('activitylog.table.properties')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('causer.name')
                    ->label(__('activitylog.table.causer_name')),
                TextColumn::make('causer.email')
                    ->label(__('activitylog.table.causer_email')),
                TextColumn::make('event')
                    ->label(__('activitylog.table.event')),
                TextColumn::make('subject_type')
                    ->label(__('activitylog.table.subject_type')),
                TextColumn::make('created_at')
                    ->label(__('activitylog.table.created_at'))
                    ->dateTime('j M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageActivityLogs::route('/'),
        ];
    }
}

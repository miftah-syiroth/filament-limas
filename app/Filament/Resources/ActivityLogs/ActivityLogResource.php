<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ManageActivityLogs;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('log_name')
                    ->label(__('activitylog.form.log_name')),
                Textarea::make('description')
                    ->label(__('activitylog.form.description'))
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('subject_type')
                    ->label(__('activitylog.form.subject_type')),
                TextInput::make('subject_id')
                    ->label(__('activitylog.form.subject_id')),
                TextInput::make('causer_type')
                    ->label(__('activitylog.form.causer_type')),
                TextInput::make('causer_id')
                    ->label(__('activitylog.form.causer_id')),
                TextInput::make('properties')
                    ->label(__('activitylog.form.properties')),
                TextInput::make('event')
                    ->label(__('activitylog.form.event')),
                TextInput::make('batch_uuid')
                    ->label(__('activitylog.form.batch_uuid')),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('activitylog.infolist.id')),
                TextEntry::make('log_name')
                    ->label(__('activitylog.infolist.log_name'))
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('activitylog.infolist.description'))
                    ->columnSpanFull(),
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
                TextEntry::make('event')
                    ->label(__('activitylog.infolist.event'))
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label(__('activitylog.infolist.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
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
                IconColumn::make('subject')
                    ->label(__('activitylog.table.subject_record'))
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
                    ->label(__('activitylog.table.properties'))
                    ->state(function (ActivityLog $record): ?bool {
                        $properties = $record->properties;

                        if ($properties === null) {
                            return null;
                        }

                        if ($properties instanceof Collection) {
                            return $properties->isEmpty() ? null : true;
                        }

                        if (is_array($properties)) {
                            return $properties === [] ? null : true;
                        }

                        return true;
                    })
                    ->icon(fn (?bool $state): ?Heroicon => $state ? Heroicon::OutlinedEye : null)
                    ->color(fn (?bool $state): ?string => $state ? 'info' : null)
                    ->url(fn (ActivityLog $record): ?string => blank($record->properties)
                            ? null
                            : route('admin.activity-logs.show', ['activityLog' => $record, 'data' => 'properties'])
                    )
                    ->openUrlInNewTab(),
                TextColumn::make('created_at')
                    ->label(__('activitylog.table.created_at'))
                    ->dateTime('j M Y H:i')
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

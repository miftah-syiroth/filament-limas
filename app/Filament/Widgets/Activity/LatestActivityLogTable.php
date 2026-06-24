<?php

namespace App\Filament\Widgets\Activity;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\ActivityLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;

class LatestActivityLogTable extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 120;

    protected int|string|array $columnSpan = 'full';

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:ActivityLog');
    // }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('dashboard.activity_log'))
            ->query(
                ActivityLog::query()
                    ->with(['causer'])
                    ->latest('created_at')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('causer.name')
                    ->label(__('activitylog.table.causer_name'))
                    ->placeholder('-'),
                TextColumn::make('event')
                    ->label(__('activitylog.table.event'))
                    ->badge(),
                TextColumn::make('subject_type')
                    ->label(__('activitylog.table.subject_type'))
                    ->formatStateUsing(fn (?string $state): string => $state
                        ? Str::afterLast($state, '\\')
                        : '-'),
                TextColumn::make('description')
                    ->label(__('activitylog.form.description'))
                    ->limit(40)
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label(__('activitylog.table.created_at'))
                    ->since(),
            ])
            ->paginated(false)
            ->recordUrl(fn (ActivityLog $record): string => ActivityLogResource::getUrl('index'));
    }
}

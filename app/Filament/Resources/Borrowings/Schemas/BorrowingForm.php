<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use App\Enums\ItemAuditCondition;
use App\Models\BorrowingItem;
use App\Models\Department;
use App\Models\Item;
use App\Models\Location;
use App\Models\Room;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BorrowingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('borrowing.form.section_borrower'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('borrowed_at')
                            ->label(__('borrowing.form.borrowed_at'))
                            ->required()
                            ->default(now()->format('m/d/Y')),
                        DatePicker::make('due_at')
                            ->label(__('borrowing.form.due_at'))
                            ->required(),
                        DatePicker::make('returned_at')
                            ->label(__('borrowing.form.returned_at'))
                            ->disabled(function (Get $get): bool {
                                $borrowingId = $get('id');

                                return BorrowingItem::where('borrowing_id', $borrowingId)
                                    ->whereNull('checked_in_at')
                                    ->exists();
                            })
                            ->visibleOn('edit'),
                        Select::make('to_location_id')
                            ->label(__('borrowing.form.to_location'))
                            ->options(Location::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('to_department_id', null);
                                $set('to_room_id', null);
                            })
                            ->preload(),
                        Select::make('to_department_id')
                            ->label(__('borrowing.form.to_department'))
                            ->options(function (Get $get): Collection {
                                $locationId = $get('to_location_id');
                                if (! $locationId) {
                                    return collect();
                                }

                                return Department::whereHas('locations', fn(Builder $query) => $query->where('location_id', $locationId))
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload(),
                        Select::make('to_room_id')
                            ->label(__('borrowing.form.to_room'))
                            ->options(function (Get $get): Collection {
                                $locationId = $get('to_location_id');
                                if (! $locationId) {
                                    return collect();
                                }

                                return Room::where('location_id', $locationId)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload(),
                        Textarea::make('notes')
                            ->label(__('borrowing.form.notes')),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use App\Enums\ItemAuditCondition;
use App\Models\BorrowingItem;
use App\Models\Item;
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
                        Select::make('user_id')
                            ->label(__('borrowing.form.user'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
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
                        Textarea::make('notes')
                            ->label(__('borrowing.form.notes')),
                    ]),
                Section::make(__('borrowing.form.section_items'))
                    ->hiddenOn('edit')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->columns(3)
                            ->schema([
                                Select::make('item_id')
                                    ->label(__('borrowing.form.item'))
                                    ->options(
                                        Item::borrowable()
                                            ->with('activeBorrowingItems')
                                            ->limit(10)
                                            ->get()
                                            ->mapWithKeys(fn (Item $item): array => [
                                                $item->id => "{$item->serial_number} - {$item->model->name}",
                                            ])->all()
                                    )
                                    ->searchable()
                                    ->getSearchResultsUsing(function (string $search): array {
                                        return Item::borrowable()
                                            ->where(function ($query) use ($search) {
                                                $query->where('serial_number', 'ilike', "{$search}%")
                                                    ->orWhere('name', 'ilike', "%{$search}%")
                                                    ->orWhereHas('model', function (Builder $query) use ($search) {
                                                        $query->where('name', 'ilike', "%{$search}%")
                                                            ->orWhereHas('category', function (Builder $query) use ($search) {
                                                                $query->where('name', 'ilike', "%{$search}%");
                                                            });
                                                    });
                                            })
                                            ->limit(20)
                                            ->get()
                                            ->mapWithKeys(fn (Item $item): array => [
                                                $item->id => "{$item->serial_number} - {$item->model?->name}",
                                            ])
                                            ->all();
                                    })
                                    ->getOptionLabelUsing(function ($value): ?string {
                                        $item = Item::find($value);

                                        return $item?->serial_number
                                            ? $item?->serial_number.' - '.$item?->model?->name
                                            : null;
                                    })
                                    ->required()
                                    ->live()
                                    ->preload()
                                    ->native(false)
                                    ->afterStateUpdated(function (Set $set, $state): void {
                                        $set('quantity', null);
                                        $set('condition_out', null);
                                        if ($state) {
                                            $item = Item::with(['latestAudit', 'activeBorrowingItems'])
                                                ->find($state);
                                            $set('quantity', $item?->borrowable_quantity ?? 1);
                                            $set('condition_out', $item?->latestAudit?->condition?->value);
                                        }
                                    }),
                                TextInput::make('quantity')
                                    ->label(__('borrowing.form.quantity'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->default(1)
                                    ->live()
                                    ->maxValue(fn (Get $get): ?int => Item::find($get('item_id'))?->quantity),
                                Select::make('condition_out')
                                    ->label(__('borrowing.form.condition_out'))
                                    ->options(ItemAuditCondition::class)
                                    ->native(false)
                                    ->required(),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get): array {
                                $borrowedAt = $get('borrowed_at')
                                    ? Carbon::parse($get('borrowed_at'))->startOfDay()
                                    : now();
                                $data['checked_out_at'] = $borrowedAt;
                                $data['condition_in'] = $data['condition_out'] ?? null;

                                return $data;
                            })
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel(__('borrowing.form.add_item_repeater')),
                    ]),
            ]);
    }
}

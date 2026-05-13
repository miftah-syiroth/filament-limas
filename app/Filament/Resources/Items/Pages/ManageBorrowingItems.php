<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Borrowings\BorrowingResource;
use App\Filament\Resources\Items\ItemResource;
use App\Models\BorrowingItem;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageBorrowingItems extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string $relationship = 'borrowingItems';

    public static function getNavigationLabel(): string
    {
        return __('items.pages.borrowing.navigation_label');
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('checked_out_at', direction: 'desc')
            ->columns([
                TextColumn::make('borrowing.user.name')
                    ->label(__('items.pages.borrowing.borrower'))
                    ->searchable(),
                TextColumn::make('borrowing.due_at')
                    ->label(__('items.pages.borrowing.due_at'))
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('borrowing.status')
                    ->label(__('items.pages.borrowing.status'))
                    ->badge()
                    ->color('primary'),
                TextColumn::make('checked_out_at')
                    ->label(__('items.pages.borrowing.checked_out_at'))
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('condition_out')
                    ->label(__('items.pages.borrowing.condition_out'))
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('checked_in_at')
                    ->label(__('items.pages.borrowing.checked_in_at'))
                    ->dateTime('j M Y'),
                TextColumn::make('condition_in')
                    ->label(__('items.pages.borrowing.condition_in'))
                    ->badge()
                    ->color('primary')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('quantity')
                    ->label(__('items.pages.borrowing.quantity'))
                    ->numeric()
                    ->alignCenter(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(__('items.pages.borrowing.view_borrowing'))
                    ->icon('heroicon-o-eye')
                    ->url(fn (BorrowingItem $record): string => BorrowingResource::getUrl('view', ['record' => $record->borrowing->id])),
            ])
            ->filters([

            ]);
    }
}

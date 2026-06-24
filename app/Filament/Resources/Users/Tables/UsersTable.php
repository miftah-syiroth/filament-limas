<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('user.table.name'))
                    ->icon(function (Model $record) {
                        // jika record->id == Auth::user()->id maka return Heroicon::OutlinedUser
                        if ($record->id == Auth::user()->id) {
                            return Heroicon::OutlinedUser;
                        }

                    })
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('user.table.email'))
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('user.table.roles'))
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('user.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('user.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('two_factor_confirmed_at')
                    ->label(__('user.table.two_factor_confirmed_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles_name')
                    ->label(__('user.table.roles'))
                    ->relationship('roles', 'name')
                    ->multiple(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
                EditAction::make()
                    ->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn (Collection $records) => $records->each->delete()),
                ]),
            ]);
    }
}

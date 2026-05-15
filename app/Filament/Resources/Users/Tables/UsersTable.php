<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('user.table.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('user.table.email'))
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('user.table.roles'))
                    ->badge(),
                ToggleColumn::make('email_verified_at')
                    ->label(__('user.table.email_verified'))
                    ->disabled(fn ($record) => Gate::denies('update', $record))
                    ->alignCenter()
                    ->getStateUsing(fn ($record): bool => $record->email_verified_at !== null)
                    ->updateStateUsing(function ($record, bool $state): void {
                        Gate::authorize('update', $record);
                        $record->update([
                            'email_verified_at' => $state ? now() : null,
                        ]);
                    }),
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
                    ->relationship('roles', 'name', hasEmptyOption: true)
                    ->emptyRelationshipOptionLabel(__('user.table.no_role'))
                    ->multiple(),
                // SelectFilter::make('role_name')
                //     ->label(__('user.table.roles'))
                //     ->multiple()
                //     ->options(Role::get()->pluck('name', 'id')),
                    // ->query(function (Builder $query, array $data): Builder {
                    //     $types = $data['values'] ?? [];
                    //     if (empty($types)) {
                    //         return $query;
                    //     }

                    //     return $query->whereHas('category', function (Builder $query) use ($types): Builder {
                    //         return $query->whereIn('type', $types);
                    //     });
                    // }),
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

<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Organization;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('organization_id')
                            ->label(__('department.form.organization'))
                            ->options(Organization::query()->pluck('name', 'id'))
                            ->default(function(): string {
                                return Organization::query()->first()->id;
                            })
                            ->disabled()
                            ->dehydrated(),
                        Select::make('locations')
                            ->label(__('department.form.location'))
                            ->relationship('locations', 'name')
                            ->multiple()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label(__('department.form.name'))
                            ->required(),
                        TextInput::make('phone')
                            ->label(__('department.form.phone'))
                            ->tel(),
                        Textarea::make('notes')
                            ->label(__('department.form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

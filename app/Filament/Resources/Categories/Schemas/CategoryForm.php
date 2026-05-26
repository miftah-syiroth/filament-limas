<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Enums\CategoryType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('category.form.name'))
                            ->required(),
                        Select::make('type')
                            ->label(__('category.form.type'))
                            ->options(CategoryType::class)
                            ->native(false)
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('category.form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

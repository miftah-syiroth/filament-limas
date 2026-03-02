<?php

namespace App\Filament\Resources\Deprecations\Schemas;

use App\Enums\DeprecationType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeprecationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('months')
                    ->label('Lama Depresiasi (bulan)')
                    ->required()
                    ->numeric(),
                TextInput::make('depreciation_min')
                    ->label('Nilai Depresiasi Minimum')
                    ->required()
                    ->numeric(),
                Select::make('depreciation_type')
                    ->options(DeprecationType::class)
                    ->native(false)
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}

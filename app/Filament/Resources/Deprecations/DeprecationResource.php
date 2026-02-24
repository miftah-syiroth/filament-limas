<?php

namespace App\Filament\Resources\Deprecations;

use App\Filament\Resources\Deprecations\Pages\CreateDeprecation;
use App\Filament\Resources\Deprecations\Pages\EditDeprecation;
use App\Filament\Resources\Deprecations\Pages\ListDeprecations;
use App\Filament\Resources\Deprecations\Pages\ViewDeprecation;
use App\Filament\Resources\Deprecations\Schemas\DeprecationForm;
use App\Filament\Resources\Deprecations\Schemas\DeprecationInfolist;
use App\Filament\Resources\Deprecations\Tables\DeprecationsTable;
use App\Models\Deprecation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeprecationResource extends Resource
{
    protected static ?string $model = Deprecation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DeprecationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DeprecationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeprecationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeprecations::route('/'),
            'create' => CreateDeprecation::route('/create'),
            'view' => ViewDeprecation::route('/{record}'),
            'edit' => EditDeprecation::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

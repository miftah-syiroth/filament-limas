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
use UnitEnum;

class DeprecationResource extends Resource
{
    protected static ?string $model = Deprecation::class;

    public static function getModelLabel(): string
    {
        return __('deprecation.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('deprecation.plural_model_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('deprecation.navigation_label');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 8;

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
                // SoftDeletingScope::class,
            ]);
    }
}

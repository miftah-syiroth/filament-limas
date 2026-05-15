<?php

namespace App\Filament\Resources\Depreciations;

use App\Filament\Resources\Depreciations\Pages\CreateDepreciation;
use App\Filament\Resources\Depreciations\Pages\EditDepreciation;
use App\Filament\Resources\Depreciations\Pages\ListDepreciations;
use App\Filament\Resources\Depreciations\Pages\ViewDepreciation;
use App\Filament\Resources\Depreciations\Schemas\DepreciationForm;
use App\Filament\Resources\Depreciations\Schemas\DepreciationInfolist;
use App\Filament\Resources\Depreciations\Tables\DepreciationsTable;
use App\Models\Depreciation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DepreciationResource extends Resource
{
    protected static ?string $model = Depreciation::class;

    public static function getModelLabel(): string
    {
        return __('depreciation.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('depreciation.plural_model_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('depreciation.navigation_label');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return DepreciationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DepreciationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepreciationsTable::configure($table);
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
            'index' => ListDepreciations::route('/'),
            'create' => CreateDepreciation::route('/create'),
            'view' => ViewDepreciation::route('/{record}'),
            'edit' => EditDepreciation::route('/{record}/edit'),
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

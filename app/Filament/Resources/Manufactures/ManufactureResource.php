<?php

namespace App\Filament\Resources\Manufactures;

use App\Filament\Resources\Manufactures\Pages\CreateManufacture;
use App\Filament\Resources\Manufactures\Pages\EditManufacture;
use App\Filament\Resources\Manufactures\Pages\ListManufactures;
use App\Filament\Resources\Manufactures\Pages\ViewManufacture;
use App\Filament\Resources\Manufactures\Schemas\ManufactureForm;
use App\Filament\Resources\Manufactures\Schemas\ManufactureInfolist;
use App\Filament\Resources\Manufactures\Tables\ManufacturesTable;
use App\Models\Manufacture;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManufactureResource extends Resource
{
    protected static ?string $model = Manufacture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CpuChip;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;
    
    public static function form(Schema $schema): Schema
    {
        return ManufactureForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ManufactureInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManufacturesTable::configure($table);
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
            'index' => ListManufactures::route('/'),
            'create' => CreateManufacture::route('/create'),
            'view' => ViewManufacture::route('/{record}'),
            'edit' => EditManufacture::route('/{record}/edit'),
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

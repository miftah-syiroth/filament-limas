<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Schemas\Schema;

/** @deprecated Use ItemCreateForm or ItemEditForm instead. */
class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return ItemEditForm::configure($schema);
    }
}

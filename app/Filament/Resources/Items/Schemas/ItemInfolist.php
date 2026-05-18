<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Filament\Infolists\Components\QrCodeEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make(__('items.infolist.sections.general'))
                            ->columnSpan(2)
                            ->columnOrder([
                                'default' => 2,
                                'lg' => 1,
                            ])
                            ->columns(2)
                            ->schema([
                                Fieldset::make(__('items.infolist.fieldsets.specification'))
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                        'xl' => 3,
                                    ])
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('serial_number')
                                            ->label(__('items.infolist.serial_number')),
                                        TextEntry::make('model.name')
                                            ->label(__('items.infolist.model')),
                                        TextEntry::make('model.category.name')
                                            ->label(__('items.infolist.category')),
                                        TextEntry::make('model.category.type')
                                            ->label(__('items.infolist.type'))
                                            ->badge()
                                            ->color(fn ($state) => $state->getColor()),
                                        IconEntry::make('is_individual_tracking')
                                            ->label(__('items.infolist.individual_tracking'))
                                            ->boolean(),
                                        TextEntry::make('model.manufacture.name')
                                            ->label(__('items.infolist.manufacturer')),
                                        TextEntry::make('model.unit.name')
                                            ->label(__('items.infolist.unit')),
                                        TextEntry::make('model.depreciation.name')
                                            ->label(__('items.infolist.depreciation')),
                                        TextEntry::make('name')
                                            ->label(__('items.infolist.name')),
                                        TextEntry::make('notes')
                                            ->label(__('items.form.notes')),
                                    ]),
                                Fieldset::make(__('items.infolist.fieldsets.transfer'))
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                        'xl' => 3,
                                    ])
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('status')
                                            ->label(__('items.infolist.status'))
                                            ->badge(),
                                        TextEntry::make('quantity')
                                            ->label(__('items.infolist.quantity'))
                                            ->formatStateUsing(fn ($state, $record): string => $record->model->unit?->name
                                                ? "{$state} {$record->model?->unit?->name}"
                                                : (string) $state),
                                        TextEntry::make('location.name')
                                            ->label(__('items.infolist.location')),
                                        TextEntry::make('user.name')
                                            ->label(__('items.infolist.responsible_person')),
                                        TextEntry::make('department.name')
                                            ->label(__('items.infolist.department')),

                                        TextEntry::make('room.name')
                                            ->label(__('items.form.room')),
                                        TextEntry::make('last_audit_date')
                                            ->label(__('items.infolist.last_audit'))
                                            ->dateTime('j M Y'),
                                        TextEntry::make('next_audit_date')
                                            ->label(__('items.infolist.next_audit'))
                                            ->dateTime('j M Y'),
                                    ]),

                            ]),
                        Section::make('')
                            ->columnOrder([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                QrCodeEntry::make('serial_number')
                                    ->hiddenLabel(),
                                SpatieMediaLibraryImageEntry::make('images')
                                    ->hiddenLabel(),
                            ]),
                    ]),
                Section::make(__('items.infolist.sections.purchase'))
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->schema([
                        TextEntry::make('purchase_date')
                            ->label(__('items.infolist.purchase_date'))
                            ->date(),
                        TextEntry::make('order_quantity')
                            ->label(__('items.infolist.order_quantity'))
                            ->numeric(),
                        TextEntry::make('purchase_price')
                            ->label(__('items.infolist.purchase_price'))
                            ->money('IDR', locale: 'id', decimalPlaces: 0),
                        TextEntry::make('depreciated_price')
                            ->label(__('items.infolist.depreciated_price'))
                            ->money('IDR', locale: 'id', decimalPlaces: 0),
                        TextEntry::make('supplier.name')
                            ->label(__('items.infolist.supplier')),
                        TextEntry::make('eol_date')
                            ->label(__('items.infolist.eol'))
                            ->date(),
                        TextEntry::make('warranty_months')
                            ->label(__('items.infolist.warranty_months'))
                            ->formatStateUsing(function (?int $state, $record): ?string {
                                if ($state === null) {
                                    return null;
                                }

                                $suffix = __('items.infolist.warranty_suffix');
                                $monthsLabel = "{$state} {$suffix}";

                                if ($record->purchase_date === null) {
                                    return $monthsLabel;
                                }

                                $endDate = $record->purchase_date
                                    ->copy()
                                    ->addMonths($state)
                                    ->locale(app()->getLocale())
                                    ->translatedFormat('j M Y');

                                return __('items.infolist.warranty_with_end', [
                                    'months' => $state,
                                    'suffix' => $suffix,
                                    'date' => $endDate,
                                ]);
                            }),
                    ]),
            ]);
    }
}

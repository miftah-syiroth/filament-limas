<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Filament\Infolists\Components\QrCodeEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
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
                            ->schema([
                                TextEntry::make('serial_number')
                                    ->inlineLabel(),
                                TextEntry::make('name')
                                    ->label(__('items.infolist.name'))
                                    ->inlineLabel(),
                                TextEntry::make('model.name')
                                    ->label(__('items.infolist.model'))
                                    ->inlineLabel(),
                                TextEntry::make('status')
                                    ->label(__('items.infolist.status'))
                                    ->badge()
                                    ->inlineLabel(),
                                TextEntry::make('model.category.type')
                                    ->label(__('items.infolist.type'))
                                    ->inlineLabel()
                                    ->badge()
                                    ->color(fn ($state) => $state->getColor()),
                                TextEntry::make('model.category.name')
                                    ->label(__('items.infolist.category'))
                                    ->inlineLabel(),
                                IconEntry::make('is_individual_tracking')
                                    ->label(__('items.infolist.individual_tracking'))
                                    ->boolean()
                                    ->inlineLabel(),
                                TextEntry::make('notes')
                                    ->inlineLabel(),

                                Grid::make(2)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('quantity')
                                            ->label(__('items.infolist.quantity'))
                                            ->formatStateUsing(fn ($state, $record): string => $record->model->unit?->name
                                                ? "{$state} {$record->model?->unit?->name}"
                                                : (string) $state),
                                        TextEntry::make('borrowable_quantity')
                                            ->label(__('items.infolist.borrowable'))
                                            ->numeric(),
                                        TextEntry::make('user.name')
                                            ->label(__('items.infolist.responsible_person')),
                                        TextEntry::make('department.name')
                                            ->label(__('items.infolist.department')),
                                        TextEntry::make('location.name')
                                            ->label(__('items.infolist.location')),
                                        TextEntry::make('latestAudit.audited_at')
                                            ->label(__('items.infolist.last_audit'))
                                            ->dateTime('j M Y'),
                                        TextEntry::make('latestAudit.next_audit_at')
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
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('purchase_date')
                                        ->date()
                                        ->inlineLabel(),
                                    TextEntry::make('purchase_price')
                                        ->money('IDR')
                                        ->inlineLabel(),
                                    TextEntry::make('depreciated_price')
                                        ->money('IDR')
                                        ->inlineLabel(),
                                    TextEntry::make('eol_date')
                                        ->label(__('items.infolist.eol'))
                                        ->date()
                                        ->inlineLabel(),
                                ]),
                                Group::make([
                                    TextEntry::make('supplier.name')
                                        ->label(__('items.infolist.supplier'))
                                        ->inlineLabel(),
                                    TextEntry::make('order_quantity')
                                        ->numeric()
                                        ->inlineLabel(),
                                    TextEntry::make('warranty_months')
                                        ->label(__('items.infolist.warranty_months'))
                                        ->inlineLabel()
                                        ->numeric()
                                        ->suffix(__('items.infolist.warranty_suffix')),
                                ]),
                            ]),

                    ]),
            ]);
    }
}

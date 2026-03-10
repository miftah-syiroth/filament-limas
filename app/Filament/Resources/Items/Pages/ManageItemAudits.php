<?php

namespace App\Filament\Resources\Items\Pages;

use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use App\Filament\Resources\Items\ItemResource;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ManageItemAudits extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    protected static string $relationship = 'audits';

    protected static ?string $navigationLabel = 'Audit';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('audited_at')
                    ->label('Tanggal Audit')
                    ->required()
                    ->default(now()->format('m/d/Y')),
                Select::make('condition')
                    ->options(ItemAuditCondition::class)
                    ->native(false)
                    ->required(),
                Select::make('result')
                    ->options(ItemAuditResult::class)
                    ->native(false)
                    ->required(),
                Toggle::make('location_verified')
                    ->label('Lokasi Diverifikasi')
                    ->inline(false),
                // jika item->model->audit_interval null, maka input next audit date
                DatePicker::make('next_audit_at')
                    ->label('Tanggal Audit Berikutnya')
                    ->required()
                    ->default(function (): Carbon {
                        $auditInterval = $this->getOwnerRecord()?->model?->audit_interval ?? 3;

                        return Carbon::now()->addMonths($auditInterval);
                    }),
                Textarea::make('notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('audited_at', direction: 'desc')
            ->columns([
                TextColumn::make('audited_at')
                    ->label('Tanggal Audit')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('condition'),
                TextColumn::make('result'),
                TextColumn::make('location_verified')
                    ->badge(),
                TextColumn::make('next_audit_at')
                    ->label('Audit Berikutnya')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('id', 'like', "%{$search}");
                    }),
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

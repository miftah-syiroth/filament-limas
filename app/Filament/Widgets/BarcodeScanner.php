<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Items\ItemResource;
use App\Models\Item;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Widgets\Widget;

class BarcodeScanner extends Widget implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public const SERIAL_NUMBER_LENGTH = 8;

    protected static ?int $sort = 2;

    protected string $view = 'filament.widgets.barcode-scanner';

    public ?string $serial_number = null;

    public ?Item $scannedItem = null;

    public function updatedSerialNumber(?string $serialNumber): void
    {
        if (blank($serialNumber)) {
            return;
        }

        $normalizedSerialNumber = $this->normalizeSerialNumber($serialNumber);

        if ($normalizedSerialNumber !== $serialNumber) {
            $this->serial_number = $normalizedSerialNumber;

            if (strlen($normalizedSerialNumber) !== self::SERIAL_NUMBER_LENGTH) {
                return;
            }

            $this->searchSerialNumber();

            return;
        }

        if (strlen($normalizedSerialNumber) !== self::SERIAL_NUMBER_LENGTH) {
            return;
        }

        $this->searchSerialNumber();
    }

    public function applyScannedSerialNumber(string $scannedValue): void
    {
        $normalizedSerialNumber = $this->normalizeSerialNumber($scannedValue);

        $this->serial_number = $normalizedSerialNumber;

        if (strlen($normalizedSerialNumber) !== self::SERIAL_NUMBER_LENGTH) {
            Notification::make()
                ->title(__('barcode_scanner.invalid_serial_number'))
                ->danger()
                ->send();

            return;
        }

        $this->searchSerialNumber();
    }

    protected function normalizeSerialNumber(string $serialNumber): string
    {
        $normalizedSerialNumber = strtoupper(trim($serialNumber));

        if (strlen($normalizedSerialNumber) > self::SERIAL_NUMBER_LENGTH) {
            return substr($normalizedSerialNumber, 0, self::SERIAL_NUMBER_LENGTH);
        }

        return $normalizedSerialNumber;
    }

    public function searchSerialNumber(): void
    {
        $this->validate([
            'serial_number' => ['required', 'string', 'size:'.self::SERIAL_NUMBER_LENGTH],
        ]);

        $item = Item::query()
            ->where('serial_number', 'ilike', $this->serial_number)
            ->first();

        if (! $item) {
            Notification::make()
                ->title(__('barcode_scanner.item_not_found'))
                ->danger()
                ->send();

            return;
        }

        $this->scannedItem = $item;

        $this->mountAction('viewScannedItem');
    }

    public function viewScannedItemAction(): Action
    {
        return Action::make('viewScannedItem')
            ->modalHeading(__('barcode_scanner.item_found_heading'))
            ->modalSubmitAction()
            ->modalSubmitActionLabel(__('barcode_scanner.view_details'))
            ->closeModalByClickingAway(false)
            ->record($this->scannedItem)
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextEntry::make('serial_number')
                            ->label(__('items.infolist.serial_number')),
                        TextEntry::make('status')
                            ->label(__('items.infolist.status'))
                            ->badge(),
                    ]),
            ])
            ->stickyModalFooter()
            ->action(function () {
                $this->redirect(ItemResource::getUrl('view', ['record' => $this->scannedItem]), true);
            });
    }
}

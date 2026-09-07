<?php

namespace App\Filament\Infolists\Components;

use App\Models\BorrowingItem;
use App\Models\Item;
use App\Services\ItemBarcodeLabelGenerator;
use Filament\Infolists\Components\Entry;
use Illuminate\Database\Eloquent\Model;

class QrCodeEntry extends Entry
{
    protected string $view = 'filament.infolists.components.qr-code-entry';

    public function getQrCodeImage(): string
    {
        $item = $this->resolveItem();

        if ($item === null || blank($item->serial_number)) {
            return '';
        }

        if (! $item->relationLoaded('model')) {
            $item->load('model');
        }

        $png = app(ItemBarcodeLabelGenerator::class)->renderLabel($item);

        return '<img src="data:image/png;base64,'.base64_encode($png).'" alt="'.e((string) $item->serial_number).'" style="width:50mm;height:25mm" />';
    }

    private function resolveItem(): ?Item
    {
        $record = $this->getRecord();

        if ($record instanceof Item) {
            return $record;
        }

        if ($record instanceof BorrowingItem) {
            return $record->item;
        }

        if ($record instanceof Model && $record->relationLoaded('item') && $record->getRelation('item') instanceof Item) {
            return $record->getRelation('item');
        }

        return null;
    }
}

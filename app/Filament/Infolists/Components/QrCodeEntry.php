<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Milon\Barcode\Facades\DNS2DFacade;

class QrCodeEntry extends Entry
{
    protected string $view = 'filament.infolists.components.qr-code-entry';

    public function getQrCodeImage(): string
    {
        $value = (string) $this->getState();

        return $value !== ''
            ? 'data:image/png;base64,'.DNS2DFacade::getBarcodePNG($value, 'QRCODE', 10, 10)
            : '';
    }
}

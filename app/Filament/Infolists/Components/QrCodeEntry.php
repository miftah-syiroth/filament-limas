<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Milon\Barcode\Facades\DNS2DFacade;

class QrCodeEntry extends Entry
{
    protected string $view = 'filament.infolists.components.qr-code-entry';

    public function generateQrCode(string $value): string
    {
        $qr = DNS2DFacade::getBarcodePNG($value, 'QRCODE', 10, 10);
        return 'data:image/png;base64,' . $qr;
    }

    public function getState(): string
    {
        return $this->generateQrCode($this->getRecord()->serial_number);
    }
}

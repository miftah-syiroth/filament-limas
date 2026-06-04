<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Milon\Barcode\Facades\DNS1DFacade;

class QrCodeEntry extends Entry
{
    protected string $view = 'filament.infolists.components.qr-code-entry';

    public function getQrCodeImage(): string
    {
        $value = (string) $this->getState();

        $barcode = DNS1DFacade::getBarcodePNG($value, 'C128', 2, 60, [1, 1, 1], true);

        if ($barcode === false || $barcode === '') {
            return '';
        }

        return '<img src="data:image/png;base64,'.$barcode.'" alt="barcode" />';
    }
}

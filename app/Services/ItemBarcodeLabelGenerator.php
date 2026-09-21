<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Collection;
use Milon\Barcode\Facades\DNS1DFacade;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class ItemBarcodeLabelGenerator
{
    public const int DPI = 300;

    public const float LABEL_WIDTH_MM = 90.0;

    public const float LABEL_HEIGHT_MM = 25.0;

    public const float A4_WIDTH_MM = 297.0;

    public const float A4_HEIGHT_MM = 210.0;

    public const int COLUMNS = 3;

    /** 7 × 25mm labels + 2mm gaps fit on A4 landscape with ~11.5mm page margins. */
    public const int ROWS = 7;

    public const float INNER_PADDING_MM = 1.5;

    public const float LABEL_GAP_MM = 2.0;

    public const int LABELS_PER_PAGE = self::COLUMNS * self::ROWS;

    public function labelWidthPx(): int
    {
        return $this->mmToPx(self::LABEL_WIDTH_MM);
    }

    public function labelHeightPx(): int
    {
        return $this->mmToPx(self::LABEL_HEIGHT_MM);
    }

    public function sheetWidthPx(): int
    {
        return $this->mmToPx(self::A4_WIDTH_MM);
    }

    public function sheetHeightPx(): int
    {
        return $this->mmToPx(self::A4_HEIGHT_MM);
    }

    public function mmToPx(float $mm): int
    {
        return (int) round($mm * self::DPI / 25.4);
    }

    /**
     * @return string Binary PNG contents
     */
    public function renderLabel(Item $item): string
    {
        $width = $this->labelWidthPx();
        $height = $this->labelHeightPx();
        $padding = $this->mmToPx(self::INNER_PADDING_MM);

        $image = imagecreatetruecolor($width, $height);
        if ($image === false) {
            throw new \RuntimeException('Unable to create barcode label image.');
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 17, 24, 39);
        $border = imagecolorallocate($image, 209, 213, 219);

        imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $white);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $border);

        $contentLeft = $padding;
        $contentTop = $padding;
        $contentRight = $width - $padding;
        $contentBottom = $height - $padding;
        $contentHeight = max(1, $contentBottom - $contentTop);

        $serial = (string) $item->serial_number;
        $modelName = (string) ($item->model?->name ?? '');
        $locationLine = $this->formatLocationRoom(
            (string) ($item->location?->name ?? ''),
            (string) ($item->room?->name ?? ''),
        );

        $logoGap = $this->mmToPx(1.0);
        $logoWidth = $this->drawLogo($image, $contentLeft, $contentTop, $contentHeight);
        $col2X = $contentLeft + ($logoWidth > 0 ? $logoWidth + $logoGap : 0);
        $col2Width = max(1, $contentRight - $col2X);

        $serialFontSize = 14.0;
        $bodyFontSize = 12.0;
        $lineGap = $this->mmToPx(0.3);
        $serialLineHeight = (int) ceil($serialFontSize * 1.25);
        $bodyLineHeight = (int) ceil($bodyFontSize * 1.25);

        $textLinesBelowBarcode = 0;
        if ($modelName !== '') {
            $textLinesBelowBarcode++;
        }
        if ($locationLine !== '') {
            $textLinesBelowBarcode++;
        }

        $reservedBelowBarcode = ($textLinesBelowBarcode * $bodyLineHeight)
            + ($textLinesBelowBarcode > 0 ? $textLinesBelowBarcode * $lineGap : 0);
        $serialBlockHeight = $serialLineHeight + $lineGap;
        $barcodeAreaHeight = max(1, $contentHeight - $serialBlockHeight - $reservedBelowBarcode);

        $cursorY = $contentTop + (int) ceil($serialFontSize);
        $this->drawCenteredText($image, $serial, $serialFontSize, $black, $col2X, $cursorY, $col2Width, bold: true);

        $barcodeTop = $contentTop + $serialBlockHeight;
        $this->drawBarcode($image, $serial, $col2X, $barcodeTop, $col2Width, $barcodeAreaHeight);

        $cursorY = $barcodeTop + $barcodeAreaHeight + $lineGap + (int) ceil($bodyFontSize);
        if ($modelName !== '') {
            $this->drawCenteredText($image, $modelName, $bodyFontSize, $black, $col2X, $cursorY, $col2Width, bold: false);
            $cursorY += $bodyLineHeight + $lineGap;
        }
        if ($locationLine !== '') {
            $this->drawCenteredText($image, $locationLine, $bodyFontSize, $black, $col2X, $cursorY, $col2Width, bold: false);
        }

        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        if ($png === false || $png === '') {
            throw new \RuntimeException('Unable to encode barcode label PNG.');
        }

        return $png;
    }

    /**
     * @param  Collection<int, Item>  $items
     * @return string Binary PNG contents
     */
    public function renderSheet(Collection $items): string
    {
        $sheetWidth = $this->sheetWidthPx();
        $sheetHeight = $this->sheetHeightPx();
        $labelWidth = $this->labelWidthPx();
        $labelHeight = $this->labelHeightPx();
        $gap = $this->mmToPx(self::LABEL_GAP_MM);

        $gridWidth = (self::COLUMNS * $labelWidth) + ((self::COLUMNS - 1) * $gap);
        $gridHeight = (self::ROWS * $labelHeight) + ((self::ROWS - 1) * $gap);
        $offsetX = (int) floor(($sheetWidth - $gridWidth) / 2);
        $offsetY = (int) floor(($sheetHeight - $gridHeight) / 2);

        $sheet = imagecreatetruecolor($sheetWidth, $sheetHeight);
        if ($sheet === false) {
            throw new \RuntimeException('Unable to create barcode sheet image.');
        }

        $white = imagecolorallocate($sheet, 255, 255, 255);
        imagefilledrectangle($sheet, 0, 0, $sheetWidth - 1, $sheetHeight - 1, $white);

        foreach ($items->values()->take(self::LABELS_PER_PAGE) as $index => $item) {
            $col = $index % self::COLUMNS;
            $row = intdiv($index, self::COLUMNS);
            $destX = $offsetX + ($col * ($labelWidth + $gap));
            $destY = $offsetY + ($row * ($labelHeight + $gap));

            $labelPng = $this->renderLabel($item);
            $label = imagecreatefromstring($labelPng);
            if ($label === false) {
                continue;
            }

            imagecopy($sheet, $label, $destX, $destY, 0, 0, $labelWidth, $labelHeight);
            imagedestroy($label);
        }

        ob_start();
        imagepng($sheet);
        $png = ob_get_clean();
        imagedestroy($sheet);

        if ($png === false || $png === '') {
            throw new \RuntimeException('Unable to encode barcode sheet PNG.');
        }

        return $png;
    }

    /**
     * @param  Collection<int, Item>  $items
     */
    public function download(Collection $items): BinaryFileResponse|StreamedResponse
    {
        $items = $items->values();

        if ($items->count() <= self::LABELS_PER_PAGE) {
            $png = $this->renderSheet($items);

            return response()->streamDownload(
                function () use ($png): void {
                    echo $png;
                },
                'item-barcodes.png',
                ['Content-Type' => 'image/png'],
            );
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'item-barcodes-');
        if ($tempPath === false) {
            throw new \RuntimeException('Unable to create temporary ZIP file.');
        }

        $zipPath = $tempPath.'.zip';
        rename($tempPath, $zipPath);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to open ZIP archive for barcode sheets.');
        }

        $page = 1;
        foreach ($items->chunk(self::LABELS_PER_PAGE) as $chunk) {
            $zip->addFromString(
                sprintf('item-barcodes-page-%d.png', $page),
                $this->renderSheet($chunk->values()),
            );
            $page++;
        }

        $zip->close();

        return response()->download($zipPath, 'item-barcodes.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    private function formatLocationRoom(string $locationName, string $roomName): string
    {
        $parts = array_values(array_filter([$locationName, $roomName], fn (string $part): bool => $part !== ''));

        return implode('; ', $parts);
    }

    /**
     * Draw the logo at full content height; width follows aspect ratio (no horizontal stretch).
     *
     * @param  \GdImage  $image
     * @return int Drawn logo width in pixels (0 when missing/unreadable)
     */
    private function drawLogo($image, int $x, int $y, int $maxHeight): int
    {
        $path = public_path('logo.png');
        if (! is_file($path)) {
            return 0;
        }

        $logo = @imagecreatefrompng($path);
        if ($logo === false) {
            return 0;
        }

        $srcWidth = imagesx($logo);
        $srcHeight = imagesy($logo);
        if ($srcWidth < 1 || $srcHeight < 1) {
            imagedestroy($logo);

            return 0;
        }

        $scale = $maxHeight / $srcHeight;
        $destWidth = max(1, (int) floor($srcWidth * $scale));
        $destHeight = max(1, (int) floor($srcHeight * $scale));
        $destY = $y + (int) floor(($maxHeight - $destHeight) / 2);

        imagealphablending($image, true);
        imagesavealpha($image, true);
        imagecopyresampled(
            $image,
            $logo,
            $x,
            $destY,
            0,
            0,
            $destWidth,
            $destHeight,
            $srcWidth,
            $srcHeight,
        );
        imagedestroy($logo);

        return $destWidth;
    }

    /**
     * @param  \GdImage  $image
     */
    private function drawBarcode($image, string $serial, int $x, int $y, int $maxWidth, int $maxHeight): void
    {
        if ($serial === '') {
            return;
        }

        $barcodeBase64 = DNS1DFacade::getBarcodePNG($serial, 'C128', 3, 80, [0, 0, 0], false);
        if ($barcodeBase64 === false || $barcodeBase64 === '') {
            return;
        }

        $barcode = imagecreatefromstring(base64_decode($barcodeBase64));
        if ($barcode === false) {
            return;
        }

        $srcWidth = imagesx($barcode);
        $srcHeight = imagesy($barcode);
        $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight);
        $destWidth = max(1, (int) floor($srcWidth * $scale));
        $destHeight = max(1, (int) floor($srcHeight * $scale));
        $destX = $x + (int) floor(($maxWidth - $destWidth) / 2);
        $destY = $y + (int) floor(($maxHeight - $destHeight) / 2);

        imagecopyresampled(
            $image,
            $barcode,
            $destX,
            $destY,
            0,
            0,
            $destWidth,
            $destHeight,
            $srcWidth,
            $srcHeight,
        );
        imagedestroy($barcode);
    }

    /**
     * @param  \GdImage  $image
     */
    private function drawCenteredText(
        $image,
        string $text,
        float $fontSize,
        int $color,
        int $x,
        int $baselineY,
        int $maxWidth,
        bool $bold,
    ): void {
        if ($text === '') {
            return;
        }

        $font = $this->fontPath($bold);
        $fitted = $this->fitText($text, $font, $fontSize, $maxWidth);
        $box = imagettfbbox($fontSize, 0, $font, $fitted);
        if ($box === false) {
            return;
        }

        $textWidth = abs($box[2] - $box[0]);
        $textX = $x + (int) floor(($maxWidth - $textWidth) / 2);
        imagettftext($image, $fontSize, 0, $textX, $baselineY, $color, $font, $fitted);
    }

    private function fitText(string $text, string $font, float $fontSize, int $maxWidth): string
    {
        if ($text === '') {
            return '';
        }

        $box = imagettfbbox($fontSize, 0, $font, $text);
        if ($box !== false && abs($box[2] - $box[0]) <= $maxWidth) {
            return $text;
        }

        $ellipsis = '…';
        $truncated = $text;
        while (mb_strlen($truncated) > 0) {
            $truncated = mb_substr($truncated, 0, -1);
            $candidate = $truncated.$ellipsis;
            $box = imagettfbbox($fontSize, 0, $font, $candidate);
            if ($box !== false && abs($box[2] - $box[0]) <= $maxWidth) {
                return $candidate;
            }
        }

        return $ellipsis;
    }

    private function fontPath(bool $bold): string
    {
        $file = $bold ? 'DejaVuSans-Bold.ttf' : 'DejaVuSans.ttf';
        $path = base_path('vendor/dompdf/dompdf/lib/fonts/'.$file);

        if (! is_file($path)) {
            throw new \RuntimeException("Font file not found: {$file}");
        }

        return $path;
    }
}

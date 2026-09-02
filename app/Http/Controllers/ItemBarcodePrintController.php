<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Milon\Barcode\Facades\DNS1DFacade;

class ItemBarcodePrintController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $itemIds = array_values(array_filter(array_map(
            static fn (string $itemId): string => trim($itemId),
            explode(',', $request->string('items')->toString()),
        )));

        abort_if($itemIds === [], 404);

        $itemPositions = collect($itemIds)->flip();

        $items = Item::query()
            ->whereKey($itemIds)
            ->with([
                'model',
                'location',
                'department',
                'room',
            ])
            ->get()
            ->sortBy(fn (Item $item): int => (int) $itemPositions->get($item->getKey(), 0))
            ->values();
        

        abort_if($items->isEmpty(), 404);

        $labels = $items->map(static function (Item $item): array {
            return [
                'item' => $item,
                'barcode' => DNS1DFacade::getBarcodePNG($item->serial_number, 'C128', 2, 60, [0, 0, 0], true),
            ];
        });

        $pdf = Pdf::loadView('pdf.items.barcodes', [
            'labels' => $labels,
        ])->setPaper('a4');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="item-barcodes.pdf"');
    }
}

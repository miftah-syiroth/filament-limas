<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ItemBarcodeLabelGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemBarcodePrintController extends Controller
{
    public function __invoke(Request $request, ItemBarcodeLabelGenerator $generator): BinaryFileResponse|StreamedResponse
    {
        Gate::authorize('viewAny', Item::class);

        $itemIds = array_values(array_filter(array_map(
            static fn (string $itemId): string => trim($itemId),
            explode(',', $request->string('items')->toString()),
        )));

        abort_if($itemIds === [], 404);

        $itemPositions = collect($itemIds)->flip();

        $items = Item::query()
            ->whereKey($itemIds)
            ->with('model')
            ->get()
            ->sortBy(fn (Item $item): int => (int) $itemPositions->get($item->getKey(), 0))
            ->values();

        abort_if($items->isEmpty(), 404);

        return $generator->download($items);
    }
}

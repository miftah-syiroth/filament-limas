<?php

namespace App\Services;

use App\Models\Item;

class ItemService
{
    public function __construct(
        protected Item $item,
    ) {}

    public function forceDelete(Item $item): void
    {
        $item->delete();
    }
}

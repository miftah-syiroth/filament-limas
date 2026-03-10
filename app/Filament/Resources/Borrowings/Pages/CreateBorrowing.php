<?php

namespace App\Filament\Resources\Borrowings\Pages;

use App\Filament\Resources\Borrowings\BorrowingResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrowing extends CreateRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $borrowedAt = isset($data['borrowed_at'])
            ? Carbon::parse($data['borrowed_at'])->startOfDay()
            : now();

        foreach ($data['items'] ?? [] as $key => $item) {
            $data['items'][$key]['checked_out_at'] = $borrowedAt;
            $data['items'][$key]['condition_out'] = $item['condition_in'] ?? null;
        }

        return $data;
    }
}

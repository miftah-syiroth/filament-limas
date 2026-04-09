<?php

namespace App\Filament\Resources\Borrowings\Pages;

use App\Enums\BorrowingStatus;
use App\Filament\Resources\Borrowings\BorrowingResource;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateBorrowing extends CreateRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = BorrowingStatus::Active;

        foreach ($data['items'] ?? [] as $key => $item) {
            $data['items'][$key]['checked_out_at'] = $data['borrowed_at'];
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $borrowing = Borrowing::create($data);

            foreach ($data['items'] ?? [] as $key => $item) {
                // update or Create
                BorrowingItem::updateOrCreate([
                    'borrowing_id' => $borrowing->id,
                    'item_id' => $item['item_id'],
                ], [
                    'quantity' => $item['quantity'],
                    'checked_out_at' => $data['borrowed_at'],
                    'condition_out' => $item['condition_out'],
                ]);
            }

            return $borrowing;
        });
    }
}

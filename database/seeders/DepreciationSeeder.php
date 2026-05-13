<?php

namespace Database\Seeders;

use App\Enums\DepreciationMethod;
use App\Models\Depreciation;
use Illuminate\Database\Seeder;

class DepreciationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Menyisipkan dua kebijakan penyusutan (iPhone & MacBook) dan mengaitkannya ke
     * model inventaris Apple pada kategori smartphone dan laptop dari {@see ModelSeeder}.
     */
    public function run(): void
    {
        Depreciation::updateOrCreate(
            ['name' => 'Depresiasi iPhone'],
            [
                'months' => 36,
                'minimum_value' => 20,
                'method' => DepreciationMethod::Amount,
                'notes' => 'Masa manfaat 36 bulan; nilai sisa minimum 20% dari harga perolehan (straight line / amount).',
            ]
        );

        Depreciation::updateOrCreate(
            ['name' => 'Depresiasi MacBook'],
            [
                'months' => 60,
                'minimum_value' => 15,
                'method' => DepreciationMethod::Amount,
                'notes' => 'Masa manfaat 60 bulan; nilai sisa minimum 15% dari harga perolehan (straight line / amount).',
            ]
        );
    }
}

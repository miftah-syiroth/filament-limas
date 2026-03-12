<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buatkan beberapa unit untuk item
        $units = [
            'pcs',
            'box',
            'roll',
            'set',
            'pair',
            'bag',
        ];

        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit,
            ]);
        }
    }
}

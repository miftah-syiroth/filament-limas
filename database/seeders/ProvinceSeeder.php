<?php

namespace Database\Seeders;

use App\Imports\ProvinceImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $province_file = resource_path('data/province.csv');
        if (! file_exists($province_file)) {
            throw new \RuntimeException("File tidak ditemukan: {$province_file}");
        }
        Excel::import(new ProvinceImport, $province_file);
    }
}

<?php

namespace Database\Seeders;

use App\Imports\CityImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city_file = resource_path('data/city.csv');
        if (! file_exists($city_file)) {
            throw new \RuntimeException("File tidak ditemukan: {$city_file}");
        }
        Excel::import(new CityImport, $city_file);
    }
}

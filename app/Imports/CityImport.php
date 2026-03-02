<?php

namespace App\Imports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CityImport implements ToModel, SkipsEmptyRows, WithHeadingRow
{
    use Importable;

    public function model(array $row): ?City
    {
        return City::updateOrCreate(
            ['code' => $row['city_code']],
            [
                'name' => $row['city_name'],
                'province_code' => $row['province_code'],
            ]
        );
    }
}

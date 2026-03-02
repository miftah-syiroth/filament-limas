<?php

namespace App\Imports;

use App\Models\Province;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class ProvinceImport implements ToModel, SkipsEmptyRows, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        return Province::updateOrCreate(
            ['code' => $row['province_code']],
            [
                'name' => $row['province_name'],
                'country_code' => $row['country_code'],
            ]
        );
    }
}

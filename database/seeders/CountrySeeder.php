<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(resource_path('data/countries.json'));

        $countries = json_decode($json, true);

        foreach ($countries as $code => $name) {
            Country::updateOrCreate([
                'code' => $code,
            ], [
                'name' => $name,
            ]);
        }
    }
}

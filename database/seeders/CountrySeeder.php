<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // hanya jalankan kalau tidak ada first row
        if (DB::table('countries')->exists()) {
            $this->command->info('Countries table is not empty, skipping seeding.');

            return;
        }
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            SupplierSeeder::class,
            DepreciationSeeder::class,
            ModelSeeder::class,
            UnitSeeder::class,
        ]);
    }
}

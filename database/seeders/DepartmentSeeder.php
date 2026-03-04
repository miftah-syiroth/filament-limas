<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a company
        $company = Company::updateOrCreate(
            ['name' => 'Universitas Harapan Bangsa'],
            [
                'email' => 'pmb@uhb.ac.id',
                'phone' => '021-1234567',
                'notes' => 'Main company',
            ]
        );

        // Create locations
        $location1 = Location::updateOrCreate(
            ['name' => 'Kampus 1', 'company_id' => $company->id],
            [
                'address' => 'Jl. Raden Patah No.100',
                'address2' => 'Kedunglongsir, Ledug, Kec. Kembaran',
                'city' => 'ID3302',
                'province' => 'ID33',
                'country' => 'ID',
                'zip' => '53182',
                'phone' => '021-1111111',
                'notes' => 'Kampus 1',
            ]
        );

        $location2 = Location::updateOrCreate(
            ['name' => 'Kampus 2', 'company_id' => $company->id],
            [
                'address' => 'Jl. KH. Wahid Hasyim No.274-A',
                'address2' => 'Windusara, Karangklesem, Kec. Purwokerto Selatan',
                'city' => 'ID3302',
                'province' => 'ID33',
                'country' => 'ID',
                'zip' => '53144',
                'phone' => '022-2222222',
                'notes' => 'Kampus 2',
            ]
        );

        // Create departments
        Department::updateOrCreate(
            ['name' => 'S1 Keperawatan', 'company_id' => $company->id],
            [
                'location_id' => $location1->id,
                'phone' => '021-1111111',
                'notes' => 'S1 Keperawatan',
            ]
        );

        Department::updateOrCreate(
            ['name' => 'S1 Farmasi', 'company_id' => $company->id],
            [
                'location_id' => $location1->id,
                'phone' => '021-1111111',
                'notes' => 'S1 Farmasi',
            ]
        );

        Department::updateOrCreate(
            ['name' => 'S1 Hukum', 'company_id' => $company->id],
            [
                'location_id' => $location2->id,
                'phone' => '022-2222222',
                'notes' => 'S1 Hukum',
            ]
        );

        Department::updateOrCreate(
            ['name' => 'S1 Informatika', 'company_id' => $company->id],
            [
                'location_id' => $location2->id,
                'phone' => '022-2222222',
                'notes' => 'S1 Informatika',
            ]
        );
    }
}

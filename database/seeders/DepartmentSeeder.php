<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Location;
use App\Models\Room;
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

        $departments = [
            ['name' => 'BAAUK', 'location' => $location1],
            ['name' => 'KKAP', 'location' => $location1],
            ['name' => 'DTSI', 'location' => $location2],
            ['name' => 'SDM', 'location' => $location2],
            ['name' => 'LPPM', 'location' => $location2],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name'], 'company_id' => $company->id],
                [
                    'location_id' => $department['location']->id,
                    'phone' => $department['location']->phone,
                    'notes' => $department['name'],
                ]
            );

            Room::updateOrCreate(
                [
                    'name' => $department['name'],
                    'location_id' => $department['location']->id,
                ],
                [
                    'capacity' => 0,
                    'notes' => $department['name'],
                ]
            );
        }
    }
}

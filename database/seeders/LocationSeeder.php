<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::updateOrCreate(
            ['name' => 'Universitas Harapan Bangsa'],
            [
                'email' => 'pmb@uhb.ac.id',
                'phone' => '021-1234567',
                'notes' => 'Main organization',
            ]
        );

        // Create locations
        $location1 = Location::updateOrCreate(
            ['name' => 'Kampus 1', 'organization_id' => $organization->id],
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
            ['name' => 'Kampus 2', 'organization_id' => $organization->id],
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
    }
}

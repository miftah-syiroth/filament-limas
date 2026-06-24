<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Supplier diselaraskan dengan katalog {@see ModelSeeder} (kertas Sidu, sandal Swallow, smartphone & laptop Apple).
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Panglima Besar Stationery',
                'address' => 'Jl. Letjend Pol. Soemarto, Karangjambu, Purwanegara, Kec. Purwokerto Utara, Kabupaten Banyumas, Jawa Tengah 53127',
                'address2' => null,
                'city' => 'ID3302',
                'province' => 'ID33',
                'country' => 'ID',
                'zip' => '53127',
                'phone' => '087778888727',
                'email' => 'info@panglimabesarstationery.id',
                'url' => null,
                'notes' => 'Supplier ATK dan kertas (HVS A4/F4, merek Sidu) untuk kebutuhan administrasi kampus.',
            ],
            [
                'name' => 'Duta Mode Purwokerto',
                'address' => 'Ruko Ex Kodim, Jl. Jend. Sudirman No.38, Kauman Lama, Purwokerto Lor, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53114',
                'address2' => null,
                'city' => 'ID3302',
                'province' => 'ID33',
                'country' => 'ID',
                'zip' => '53114',
                'phone' => '081388130164',
                'email' => 'dutamode.pwt@gmail.com',
                'url' => null,
                'notes' => 'Supplier sandal dan alas kaki (merek Swallow) untuk operasional lapangan dan gudang.',
            ],
            [
                'name' => 'ELS Computer Purwokerto',
                'address' => 'Jl. Kapten Jl. Pierre Tendean No.11, Kauman Lama, Purwokerto Lor, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53114',
                'address2' => null,
                'city' => 'ID3302',
                'province' => 'ID33',
                'country' => 'ID',
                'zip' => '53114',
                'phone' => '08989203040',
                'email' => 'purwokerto@els.id',
                'url' => 'https://www.els.id/',
                'notes' => 'Supplier smartphone dan laptop Apple (iPhone, MacBook) untuk keperluan TI dan administrasi.',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );
        }
    }
}

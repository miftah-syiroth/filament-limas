<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Toko Komputer',
                'address' => 'Jl. Merdeka No. 1',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001001',
                'email' => 'info@tokokomputer.com',
                'url' => 'https://tokokomputer.com',
                'notes' => 'Komputer dan laptop untuk kampus',
            ],
            [
                'name' => 'Toko Obat Farmacare',
                'address' => 'Jl. Sudirman No. 10',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001002',
                'email' => 'info@farmacare.com',
                'url' => 'https://farmacare.com',
                'notes' => 'Obat dan peralatan medis',
            ],
            [
                'name' => 'Toko Furniture Kantor',
                'address' => 'Jl. Ahmad Yani No. 15',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001003',
                'email' => 'info@furnikantor.com',
                'url' => 'https://furnikantor.com',
                'notes' => 'Furniture kantor dan ruang kampus',
            ],
            [
                'name' => 'Toko Alat Tulis Pratama',
                'address' => 'Jl. Diponegoro No. 20',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001004',
                'email' => 'info@pratama.com',
                'url' => 'https://pratama.com',
                'notes' => 'Alat tulis dan perlengkapan sekolah',
            ],
            [
                'name' => 'Toko Alat Kesehatan Medlab',
                'address' => 'Jl. Jenderal Gatot Subroto No. 5',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001005',
                'email' => 'info@medlab.com',
                'url' => 'https://medlab.com',
                'notes' => 'Peralatan laboratorium kesehatan',
            ],
            [
                'name' => 'Toko Buku Ilmu',
                'address' => 'Jl. Gajah Mada No. 25',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001006',
                'email' => 'info@bukuilmu.com',
                'url' => 'https://bukuilmu.com',
                'notes' => 'Buku pelajaran dan referensi akademik',
            ],
            [
                'name' => 'Toko Peralatan Lab Sains',
                'address' => 'Jl. Imam Bonjol No. 30',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001007',
                'email' => 'info@labsains.com',
                'url' => 'https://labsains.com',
                'notes' => 'Peralatan laboratorium sains dan kimia',
            ],
            [
                'name' => 'Toko Seragam Unggul',
                'address' => 'Jl. Kartini No. 12',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001008',
                'email' => 'info@seragamunggul.com',
                'url' => 'https://seragamunggul.com',
                'notes' => 'Seragam sekolah dan kampus',
            ],
            [
                'name' => 'Toko Perlengkapan Kantor Citra',
                'address' => 'Jl. Pangeran Diponegoro No. 8',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001009',
                'email' => 'info@citraperlengkapan.com',
                'url' => 'https://citraperlengkapan.com',
                'notes' => 'Perlengkapan kantor lengkap',
            ],
            [
                'name' => 'Toko Alat Olahraga Jaya',
                'address' => 'Jl. Basuki Rahmat No. 18',
                'city' => 'ID3302',
                'state' => 'ID33',
                'country' => 'ID',
                'zip' => '53100',
                'phone' => '0281-1001010',
                'email' => 'info@olahraga.com',
                'url' => 'https://olahraga.com',
                'notes' => 'Alat olahraga untuk kampus dan sekolah',
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

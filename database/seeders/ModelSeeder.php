<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Depreciation;
use App\Models\Manufacture;
use App\Models\Model as InventoryModel;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iphoneDepreciation = Depreciation::query()
            ->where('name', 'Depresiasi iPhone')
            ->first();

        $macbookDepreciation = Depreciation::query()
            ->where('name', 'Depresiasi MacBook')
            ->first();

        $apple = Manufacture::updateOrCreate(
            ['name' => 'Apple'],
            [
                'url' => 'https://www.apple.com',
                'support_url' => 'https://support.apple.com',
                'support_phone' => '0008000401966',
                'support_email' => 'support@apple.com',
                'warranty_lookup_url' => 'https://checkcoverage.apple.com',
                'notes' => 'Produsen perangkat elektronik konsumen (iPhone, Mac, iPad, dll.)',
            ]
        );

        $swallow = Manufacture::updateOrCreate(
            ['name' => 'Swallow'],
            [
                'url' => 'https://www.swallow.co.id',
                'support_url' => 'https://www.swallow.co.id',
                'support_phone' => '+62-21-384-3838',
                'support_email' => 'info@swallow.co.id',
                'warranty_lookup_url' => null,
                'notes' => 'Produsen sandal dan alas kaki karet Indonesia',
            ]
        );

        $sidu = Manufacture::updateOrCreate(
            ['name' => 'Sidu'],
            [
                'url' => 'https://www.sidu.com',
                'support_url' => 'https://www.sidu.com',
                'support_phone' => '+62-21-460-3333',
                'support_email' => 'contact@sidu.com',
                'warranty_lookup_url' => null,
                'notes' => 'Merek perlengkapan kantor dan kertas (Sinar Dunia)',
            ]
        );

        $smartphone = Category::updateOrCreate(
            ['name' => 'smartphone'],
            [
                'type' => CategoryType::Asset,
                'notes' => 'Telepon pintar untuk keperluan administrasi dan komunikasi',
            ]
        );

        $laptop = Category::updateOrCreate(
            ['name' => 'laptop'],
            [
                'type' => CategoryType::Asset,
                'notes' => 'Komputer jinjing untuk perkantoran dan pembelajaran',
            ]
        );

        $sandal = Category::updateOrCreate(
            ['name' => 'sandal'],
            [
                'type' => CategoryType::Accessory,
                'notes' => 'Sandal serbaguna untuk operasional lapangan dan gudang',
            ]
        );

        $kertas = Category::updateOrCreate(
            ['name' => 'kertas'],
            [
                'type' => CategoryType::Consumable,
                'notes' => 'Kertas cetak untuk dokumen dan administrasi',
            ]
        );

        $smartphoneModels = [
            [
                'name' => 'iPhone 14 128GB',
                'model_number' => 'AAPL-IPH14-128',
                'min_amount' => 1,
                'end_of_life' => 36,
                'audit_interval' => 12,
                'notes' => 'Seri iPhone 14, layar Super Retina XDR 6.1", chip A15 Bionic, warna umum Midnight/Starlight',
            ],
            [
                'name' => 'iPhone 14 Pro 256GB',
                'model_number' => 'AAPL-IPH14P-256',
                'min_amount' => 1,
                'end_of_life' => 36,
                'audit_interval' => 12,
                'notes' => 'Seri iPhone 14 Pro, layar 6.1" ProMotion, chip A16 Bionic, kamera 48 MP',
            ],
            [
                'name' => 'iPhone 15 128GB',
                'model_number' => 'AAPL-IPH15-128',
                'min_amount' => 1,
                'end_of_life' => 36,
                'audit_interval' => 12,
                'notes' => 'Seri iPhone 15, USB-C, Dynamic Island, chip A16 Bionic',
            ],
            [
                'name' => 'iPhone 15 Pro 256GB',
                'model_number' => 'AAPL-IPH15P-256',
                'min_amount' => 1,
                'end_of_life' => 36,
                'audit_interval' => 12,
                'notes' => 'Seri iPhone 15 Pro, rangka titanium, chip A17 Pro, Action Button',
            ],
        ];

        $laptopModels = [
            [
                'name' => 'MacBook Air 13" M2 256GB',
                'model_number' => 'AAPL-MBA13-M2-256',
                'min_amount' => 1,
                'end_of_life' => 48,
                'audit_interval' => 12,
                'notes' => 'MacBook Air 13 inci, Apple M2, RAM 8 GB unified, SSD 256 GB',
            ],
            [
                'name' => 'MacBook Air 15" M2 512GB',
                'model_number' => 'AAPL-MBA15-M2-512',
                'min_amount' => 1,
                'end_of_life' => 48,
                'audit_interval' => 12,
                'notes' => 'MacBook Air 15 inci, Apple M2, layar Liquid Retina lebih besar',
            ],
            [
                'name' => 'MacBook Pro 14" M3 512GB',
                'model_number' => 'AAPL-MBP14-M3-512',
                'min_amount' => 1,
                'end_of_life' => 60,
                'audit_interval' => 12,
                'notes' => 'MacBook Pro 14 inci, chip Apple M3, cocok untuk desain ringan dan administrasi',
            ],
            [
                'name' => 'MacBook Pro 16" M3 Pro 512GB',
                'model_number' => 'AAPL-MBP16-M3PRO-512',
                'min_amount' => 1,
                'end_of_life' => 60,
                'audit_interval' => 12,
                'notes' => 'MacBook Pro 16 inci, Apple M3 Pro, performa tinggi untuk produktivitas berat',
            ],
        ];

        $sandalModels = [
            [
                'name' => 'sandal batik',
                'model_number' => 'SWL-BATIK-001',
                'min_amount' => 2,
                'end_of_life' => 24,
                'audit_interval' => 12,
                'notes' => 'Sandal karet motif batik, sol anti slip, ukuran campur',
            ],
            [
                'name' => 'sandal polos',
                'model_number' => 'SWL-POLOS-001',
                'min_amount' => 2,
                'end_of_life' => 24,
                'audit_interval' => 12,
                'notes' => 'Sandal karet polos warna netral untuk seragam operasional',
            ],
        ];

        $kertasModels = [
            [
                'name' => 'kertas A4',
                'model_number' => 'SIDU-A4-80-500',
                'min_amount' => 10,
                'end_of_life' => null,
                'audit_interval' => 6,
                'notes' => 'HVS A4 80 gsm, isi ±500 lembar per rim, putih standar fotokopi',
            ],
            [
                'name' => 'Kertas F4',
                'model_number' => 'SIDU-F4-80-500',
                'min_amount' => 10,
                'end_of_life' => null,
                'audit_interval' => 6,
                'notes' => 'HVS F4/Folio 80 gsm, isi ±500 lembar per rim, untuk dokumen legal Indonesia',
            ],
        ];

        foreach ($smartphoneModels as $modelData) {
            $this->upsertInventoryModel($apple, $smartphone, $modelData, $iphoneDepreciation?->id);
        }

        foreach ($laptopModels as $modelData) {
            $this->upsertInventoryModel($apple, $laptop, $modelData, $macbookDepreciation?->id);
        }

        foreach ($sandalModels as $modelData) {
            $this->upsertInventoryModel($swallow, $sandal, $modelData);
        }

        foreach ($kertasModels as $modelData) {
            $this->upsertInventoryModel($sidu, $kertas, $modelData);
        }
    }

    /**
     * @param  array{name: string, model_number: string, min_amount: int, end_of_life: int|null, audit_interval: int, notes: string}  $modelData
     */
    private function upsertInventoryModel(
        Manufacture $manufacture,
        Category $category,
        array $modelData,
        ?string $depreciationId = null,
    ): void {
        InventoryModel::updateOrCreate(
            [
                'name' => $modelData['name'],
                'manufacture_id' => $manufacture->id,
                'category_id' => $category->id,
            ],
            [
                'model_number' => $modelData['model_number'],
                'min_amount' => $modelData['min_amount'],
                'end_of_life' => $modelData['end_of_life'],
                'depreciation_id' => $depreciationId,
                'audit_interval' => $modelData['audit_interval'],
                'notes' => $modelData['notes'],
            ]
        );
    }
}

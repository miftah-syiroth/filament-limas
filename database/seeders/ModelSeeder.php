<?php

namespace Database\Seeders;

use App\Models\Category;
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
        $catalog = [
            [
                'manufacture' => [
                    'name' => 'Pyrex',
                    'url' => 'https://www.pyrex.com',
                    'support_url' => 'https://www.pyrex.com/customer-service',
                    'support_phone' => '1-800-999-3436',
                    'support_email' => 'support@pyrex.com',
                    'warranty_lookup_url' => 'https://www.pyrex.com/warranty',
                    'notes' => 'Peralatan laboratorium gelas untuk pendidikan',
                ],
                'categories' => [
                    [
                        'name' => 'Gelas kaca',
                        'type' => 'consumable',
                        'notes' => 'Gelas kaca untuk laboratorium kimia dan praktikum',
                        'models' => [
                            ['name' => 'Beaker Low Form 250ml', 'model_number' => 'PYX-BKR-250'],
                            ['name' => 'Beaker Low Form 500ml', 'model_number' => 'PYX-BKR-500'],
                            ['name' => 'Graduated Cylinder 100ml', 'model_number' => 'PYX-GC-100'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Schott Duran',
                    'url' => 'https://www.schott.com',
                    'support_url' => 'https://www.schott.com/en-us/contact',
                    'support_phone' => '+49-6131-66-0',
                    'support_email' => 'info@schott.com',
                    'warranty_lookup_url' => 'https://www.schott.com/en-us/service',
                    'notes' => 'Peralatan laboratorium presisi untuk riset',
                ],
                'categories' => [
                    [
                        'name' => 'Tabung reaksi',
                        'type' => 'consumable',
                        'notes' => 'Tabung reaksi untuk eksperimen laboratorium',
                        'models' => [
                            ['name' => 'DURAN Test Tube 18x180mm', 'model_number' => 'SCH-TT-18180'],
                            ['name' => 'DURAN Test Tube 25x150mm', 'model_number' => 'SCH-TT-25150'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Omron Healthcare',
                    'url' => 'https://www.omronhealthcare.com',
                    'support_url' => 'https://www.omronhealthcare.com/support',
                    'support_phone' => '1-800-634-4350',
                    'support_email' => 'support@omronhealthcare.com',
                    'warranty_lookup_url' => 'https://www.omronhealthcare.com/warranty',
                    'notes' => 'Peralatan medis untuk klinik kampus',
                ],
                'categories' => [
                    [
                        'name' => 'Nebulizer',
                        'type' => 'asset',
                        'notes' => 'Nebulizer untuk laboratorium kesehatan dan klinik',
                        'models' => [
                            ['name' => 'CompAir Elite NE-C30', 'model_number' => 'OMR-NE-C30'],
                            ['name' => 'MicroAir NE-U22', 'model_number' => 'OMR-NE-U22'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Philips',
                    'url' => 'https://www.philips.com',
                    'support_url' => 'https://www.philips.com/support',
                    'support_phone' => '1-800-243-7884',
                    'support_email' => 'support@philips.com',
                    'warranty_lookup_url' => 'https://www.philips.com/support/warranty',
                    'notes' => 'Peralatan elektronik untuk kantin dan laboratorium',
                ],
                'categories' => [
                    [
                        'name' => 'Blender',
                        'type' => 'asset',
                        'notes' => 'Blender untuk kantin kampus dan lab nutrisi',
                        'models' => [
                            ['name' => 'HR2223 ProBlend 6 3D', 'model_number' => 'PH-HR2223'],
                            ['name' => 'Daily Collection HR2052', 'model_number' => 'PH-HR2052'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Omron Healthcare',
                    'url' => 'https://www.omronhealthcare.com',
                    'support_url' => 'https://www.omronhealthcare.com/support',
                    'support_phone' => '1-800-634-4350',
                    'support_email' => 'support@omronhealthcare.com',
                    'warranty_lookup_url' => 'https://www.omronhealthcare.com/warranty',
                    'notes' => 'Peralatan medis untuk klinik kampus',
                ],
                'categories' => [
                    [
                        'name' => 'Termogun',
                        'type' => 'asset',
                        'notes' => 'Termometer infrared untuk skrining suhu tubuh',
                        'models' => [
                            ['name' => 'MC-720 Non-Contact Thermometer', 'model_number' => 'OMR-MC720'],
                            ['name' => 'MC-623 Instant Thermometer', 'model_number' => 'OMR-MC623'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($catalog as $entry) {
            $manufacture = Manufacture::updateOrCreate(
                ['name' => $entry['manufacture']['name']],
                $entry['manufacture']
            );

            foreach ($entry['categories'] as $categoryData) {
                $category = Category::updateOrCreate(
                    ['name' => $categoryData['name']],
                    [
                        'type' => $categoryData['type'],
                        'notes' => $categoryData['notes'],
                    ]
                );

                foreach ($categoryData['models'] as $modelData) {
                    InventoryModel::updateOrCreate(
                        [
                            'name' => $modelData['name'],
                            'manufacture_id' => $manufacture->id,
                            'category_id' => $category->id,
                        ],
                        [
                            'model_number' => $modelData['model_number'],
                            'min_amount' => 1,
                            'end_of_life' => 60,
                            'notes' => "{$manufacture->name} - {$category->name}",
                        ]
                    );
                }
            }
        }
    }
}

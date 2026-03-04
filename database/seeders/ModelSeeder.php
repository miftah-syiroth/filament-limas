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
                    'name' => 'Apple',
                    'url' => 'https://www.apple.com',
                    'support_url' => 'https://support.apple.com',
                    'support_phone' => '0800-1-027753',
                    'support_email' => 'support@apple.com',
                    'warranty_lookup_url' => 'https://checkcoverage.apple.com',
                    'notes' => 'Perangkat digital untuk office dan universitas',
                ],
                'categories' => [
                    [
                        'name' => 'Smartwatch',
                        'type' => 'asset',
                        'notes' => 'Perangkat wearable untuk operasional kampus',
                        'models' => [
                            ['name' => 'Apple Watch Series 8', 'model_number' => 'A2701'],
                            ['name' => 'Apple Watch Ultra 2', 'model_number' => 'A2702'],
                        ],
                    ],
                    [
                        'name' => 'Tablet',
                        'type' => 'asset',
                        'notes' => 'Tablet untuk presentasi dan mobile learning',
                        'models' => [
                            ['name' => 'iPad Air 11', 'model_number' => 'A2902'],
                            ['name' => 'iPad Pro 13', 'model_number' => 'A2925'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Lenovo',
                    'url' => 'https://www.lenovo.com',
                    'support_url' => 'https://support.lenovo.com',
                    'support_phone' => '001-803-016-0135',
                    'support_email' => 'support@lenovo.com',
                    'warranty_lookup_url' => 'https://pcsupport.lenovo.com/warrantylookup',
                    'notes' => 'Laptop dan desktop untuk sekolah dan universitas',
                ],
                'categories' => [
                    [
                        'name' => 'Laptop',
                        'type' => 'asset',
                        'notes' => 'Perangkat kelas digital untuk sekolah dan universitas',
                        'models' => [
                            ['name' => 'IdeaPad Flex 5 Laptop', 'model_number' => 'IP-FLEX5'],
                            ['name' => 'ThinkPad C14 Laptop', 'model_number' => 'TP-C14'],
                        ],
                    ],
                    [
                        'name' => 'Monitor',
                        'type' => 'asset',
                        'notes' => 'PC all-in-one untuk front office dan perpustakaan',
                        'models' => [
                            ['name' => 'ThinkVision P49w-30', 'model_number' => 'IC-AIO2'],
                            ['name' => 'ThinkVision P32UD-40', 'model_number' => 'IC-AIO3'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'HP',
                    'url' => 'https://www.hp.com',
                    'support_url' => 'https://support.hp.com',
                    'support_phone' => '001-803-016-3074',
                    'support_email' => 'support@hp.com',
                    'warranty_lookup_url' => 'https://support.hp.com/check-warranty',
                    'notes' => 'Komputer dan printer untuk office dan sekolah',
                ],
                'categories' => [
                    [
                        'name' => 'Printer',
                        'type' => 'asset',
                        'notes' => 'Printer dokumen akademik dan administrasi',
                        'models' => [
                            ['name' => 'LaserJet Pro M404dn', 'model_number' => 'LJ-M404DN'],
                            ['name' => 'Smart Tank 7602', 'model_number' => 'ST-7602'],
                        ],
                    ],
                    [
                        'name' => 'Scanner',
                        'type' => 'asset',
                        'notes' => 'Scanner dokumen arsip office dan administrasi',
                        'models' => [
                            ['name' => 'ScanJet Pro N4000 snw1', 'model_number' => 'SJ-N4000'],
                            ['name' => 'ScanJet Enterprise Flow 7000', 'model_number' => 'SJ-E7000'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'IKEA',
                    'url' => 'https://www.ikea.com',
                    'support_url' => 'https://www.ikea.com/id/id/customer-service',
                    'support_phone' => '021-29853900',
                    'support_email' => 'customer.relations.id@ikea.com',
                    'warranty_lookup_url' => 'https://www.ikea.com/id/id/customer-service/guarantee',
                    'notes' => 'Furniture untuk office, sekolah, dan universitas',
                ],
                'categories' => [
                    [
                        'name' => 'Meja',
                        'type' => 'asset',
                        'notes' => 'Meja kerja ergonomis untuk office dan laboratorium',
                        'models' => [
                            ['name' => 'BEKANT Desk 160x80', 'model_number' => 'BEK-16080'],
                            ['name' => 'LAGKAPTEN / ALEX Desk', 'model_number' => 'LAG-ALEX'],
                        ],
                    ],
                    [
                        'name' => 'Lemari',
                        'type' => 'asset',
                        'notes' => 'Lemari penyimpanan dokumen office dan sekolah',
                        'models' => [
                            ['name' => 'GALANT Cabinet', 'model_number' => 'GALANT-CAB'],
                            ['name' => 'IDANAS Cabinet', 'model_number' => 'IDANAS-CAB'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Olympus',
                    'url' => 'https://www.olympus-lifescience.com',
                    'support_url' => 'https://www.olympus-lifescience.com/en/support',
                    'support_phone' => '+81-3-3340-2111',
                    'support_email' => 'lifescience.support@olympus.com',
                    'warranty_lookup_url' => 'https://www.olympus-lifescience.com/en/support/service',
                    'notes' => 'Perangkat laboratorium kesehatan dan riset universitas',
                ],
                'categories' => [
                    [
                        'name' => 'Mikroskop',
                        'type' => 'asset',
                        'notes' => 'Mikroskop untuk laboratorium kesehatan',
                        'models' => [
                            ['name' => 'CX23 Microscope', 'model_number' => 'CX23'],
                            ['name' => 'BX53 Microscope', 'model_number' => 'BX53'],
                        ],
                    ],
                    [
                        'name' => 'Endoscopy',
                        'type' => 'asset',
                        'notes' => 'Perangkat endoskopi untuk lab klinik pendidikan',
                        'models' => [
                            ['name' => 'EVIS EXERA III CV-190', 'model_number' => 'CV-190'],
                            ['name' => 'GIF-H190 Gastroscope', 'model_number' => 'GIF-H190'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Gramedia',
                    'url' => 'https://www.gramedia.com',
                    'support_url' => 'https://www.gramedia.com/hubungikami',
                    'support_phone' => '021-53650110',
                    'support_email' => 'customercare@gramedia.com',
                    'warranty_lookup_url' => 'https://www.gramedia.com/hubungikami',
                    'notes' => 'Penyedia buku untuk sekolah dan universitas',
                ],
                'categories' => [
                    [
                        'name' => 'Buku Medis',
                        'type' => 'consumable',
                        'notes' => 'Buku referensi laboratorium kesehatan',
                        'models' => [
                            ['name' => 'Atlas Anatomi Sobotta', 'model_number' => 'BK-MED-001'],
                            ['name' => 'Patologi Robbins', 'model_number' => 'BK-MED-002'],
                        ],
                    ],
                    [
                        'name' => 'Buku Teknik',
                        'type' => 'consumable',
                        'notes' => 'Buku komputer dan teknik untuk universitas',
                        'models' => [
                            ['name' => 'Basis Data Lanjut', 'model_number' => 'BK-ENG-001'],
                            ['name' => 'Jaringan Komputer Modern', 'model_number' => 'BK-ENG-002'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Nike',
                    'url' => 'https://www.nike.com',
                    'support_url' => 'https://www.nike.com/help',
                    'support_phone' => '001-803-016-0217',
                    'support_email' => 'support@nike.com',
                    'warranty_lookup_url' => 'https://www.nike.com/help/a/returns-policy',
                    'notes' => 'Peralatan olahraga untuk sekolah dan universitas',
                ],
                'categories' => [
                    [
                        'name' => 'Sepatu Lari',
                        'type' => 'accessory',
                        'notes' => 'Sepatu olahraga untuk kegiatan kampus',
                        'models' => [
                            ['name' => 'Air Zoom Pegasus 40', 'model_number' => 'NK-PGS40'],
                            ['name' => 'Revolution 7', 'model_number' => 'NK-RV7'],
                        ],
                    ],
                    [
                        'name' => 'Bola Olahraga',
                        'type' => 'accessory',
                        'notes' => 'Peralatan bola untuk unit olahraga sekolah',
                        'models' => [
                            ['name' => 'Nike Academy Team Ball', 'model_number' => 'NK-ATB-5'],
                            ['name' => 'Nike Pitch Training Ball', 'model_number' => 'NK-PTB-5'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Adidas',
                    'url' => 'https://www.adidas.com',
                    'support_url' => 'https://www.adidas.com/us/help',
                    'support_phone' => '001-803-016-0198',
                    'support_email' => 'support@adidas.com',
                    'warranty_lookup_url' => 'https://www.adidas.com/us/help-topics-returns_and_refunds.html',
                    'notes' => 'Fashion dan olahraga untuk institusi pendidikan',
                ],
                'categories' => [
                    [
                        'name' => 'Jersey Olahraga',
                        'type' => 'consumable',
                        'notes' => 'Jersey tim untuk kegiatan olahraga sekolah',
                        'models' => [
                            ['name' => 'Entrada 22 Jersey', 'model_number' => 'AD-ENT22'],
                            ['name' => 'Tiro 24 Jersey', 'model_number' => 'AD-TIRO24'],
                        ],
                    ],
                    [
                        'name' => 'Tas Olahraga',
                        'type' => 'accessory',
                        'notes' => 'Tas gym dan training untuk mahasiswa',
                        'models' => [
                            ['name' => 'Tiro Duffel Bag M', 'model_number' => 'AD-DFL-M'],
                            ['name' => 'Classic Badge Backpack', 'model_number' => 'AD-BBP-CL'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Manduka',
                    'url' => 'https://www.manduka.com',
                    'support_url' => 'https://www.manduka.com/pages/contact-us',
                    'support_phone' => '1-800-608-6000',
                    'support_email' => 'support@manduka.com',
                    'warranty_lookup_url' => 'https://www.manduka.com/pages/warranty',
                    'notes' => 'Perlengkapan yoga untuk unit olahraga kampus',
                ],
                'categories' => [
                    [
                        'name' => 'Matras Yoga',
                        'type' => 'accessory',
                        'notes' => 'Matras yoga untuk kelas olahraga dan wellness',
                        'models' => [
                            ['name' => 'PRO Yoga Mat 6mm', 'model_number' => 'MDK-PRO-6MM'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Dr. Martens',
                    'url' => 'https://www.drmartens.com',
                    'support_url' => 'https://www.drmartens.com/us/en/customer-service',
                    'support_phone' => '1-866-362-8533',
                    'support_email' => 'support@drmartens.com',
                    'warranty_lookup_url' => 'https://www.drmartens.com/us/en/customer-service',
                    'notes' => 'Sepatu safety untuk laboratorium dan lapangan',
                ],
                'categories' => [
                    [
                        'name' => 'Sepatu Boot',
                        'type' => 'accessory',
                        'notes' => 'Sepatu boot untuk praktikum lapangan dan lab',
                        'models' => [
                            ['name' => '1460 Safety Boot', 'model_number' => 'DM-1460-SFT'],
                        ],
                    ],
                ],
            ],
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
                        'name' => 'Gelas Ukur',
                        'type' => 'asset',
                        'notes' => 'Gelas ukur untuk laboratorium kimia',
                        'models' => [
                            ['name' => 'Graduated Beaker 500ml', 'model_number' => 'PYX-BKR-500'],
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
                        'name' => 'Tabung Reaksi',
                        'type' => 'consumable',
                        'notes' => 'Tabung reaksi untuk eksperimen laboratorium',
                        'models' => [
                            ['name' => 'DURAN Test Tube 18x180mm', 'model_number' => 'SCH-TT-18180'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Maspion',
                    'url' => 'https://www.maspion.com',
                    'support_url' => 'https://www.maspion.com/contact',
                    'support_phone' => '031-3981888',
                    'support_email' => 'customercare@maspion.com',
                    'warranty_lookup_url' => 'https://www.maspion.com/warranty',
                    'notes' => 'Peralatan rumah tangga untuk kantin dan asrama',
                ],
                'categories' => [
                    [
                        'name' => 'Gayung',
                        'type' => 'consumable',
                        'notes' => 'Gayung untuk fasilitas kamar mandi kampus',
                        'models' => [
                            ['name' => 'Gayung Plastik 2L', 'model_number' => 'MSP-GYG-2L'],
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Thermos',
                    'url' => 'https://www.thermos.com',
                    'support_url' => 'https://www.thermos.com/customer-service',
                    'support_phone' => '1-800-243-0745',
                    'support_email' => 'support@thermos.com',
                    'warranty_lookup_url' => 'https://www.thermos.com/warranty',
                    'notes' => 'Peralatan termos untuk kantin dan event kampus',
                ],
                'categories' => [
                    [
                        'name' => 'Termos',
                        'type' => 'accessory',
                        'notes' => 'Termos untuk kegiatan kantin dan acara kampus',
                        'models' => [
                            ['name' => 'Stainless Steel Vacuum Insulated 1.2L', 'model_number' => 'THM-VAC-1.2L'],
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
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'B. Braun',
                    'url' => 'https://www.bbraun.com',
                    'support_url' => 'https://www.bbraun.com/en/support.html',
                    'support_phone' => '+49-5661-71-0',
                    'support_email' => 'support@bbraun.com',
                    'warranty_lookup_url' => 'https://www.bbraun.com/en/service.html',
                    'notes' => 'Peralatan medis presisi untuk laboratorium kesehatan',
                ],
                'categories' => [
                    [
                        'name' => 'Infus Pump',
                        'type' => 'asset',
                        'notes' => 'Infusion pump untuk praktikum keperawatan',
                        'models' => [
                            ['name' => 'Infusomat Space Pump', 'model_number' => 'BBR-ISP-001'],
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
                        ],
                    ],
                ],
            ],
            [
                'manufacture' => [
                    'name' => 'Dräger',
                    'url' => 'https://www.draeger.com',
                    'support_url' => 'https://www.draeger.com/en/Support',
                    'support_phone' => '+49-451-882-0',
                    'support_email' => 'info@draeger.com',
                    'warranty_lookup_url' => 'https://www.draeger.com/en/Service',
                    'notes' => 'Peralatan ventilasi medis untuk lab keperawatan',
                ],
                'categories' => [
                    [
                        'name' => 'Ventilator',
                        'type' => 'asset',
                        'notes' => 'Ventilator untuk praktikum lab keperawatan',
                        'models' => [
                            ['name' => 'Evita V300', 'model_number' => 'DRG-EV300'],
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

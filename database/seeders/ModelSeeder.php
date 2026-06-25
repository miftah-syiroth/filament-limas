<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Enums\ItemAuditCondition;
use App\Enums\ItemAuditResult;
use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Enums\StockMovementType;
use App\Models\Category;
use App\Models\Depreciation;
use App\Models\Item;
use App\Models\ItemAudit;
use App\Models\ItemStateLog;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\Manufacture;
use App\Models\Model as InventoryModel;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use App\Support\ItemSerialNumber;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ModelSeeder extends Seeder
{
    /** @var Collection<int, string> */
    private Collection $eligibleUserIds;

    /** @var Collection<int, Location> */
    private Collection $locations;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $smartPhoneLaptopSupplier = Supplier::query()
            ->where('name', 'ELS Computer Purwokerto')
            ->first();

        $sandalSupplier = Supplier::query()
            ->where('name', 'Duta Mode Purwokerto')
            ->first();

        $kertasSupplier = Supplier::query()
            ->where('name', 'Panglima Besar Stationery')
            ->first();

        $iphoneDepreciation = Depreciation::query()
            ->where('name', 'Depresiasi iPhone')
            ->first();

        $macbookDepreciation = Depreciation::query()
            ->where('name', 'Depresiasi MacBook')
            ->first();

        $this->eligibleUserIds = User::query()
            ->whereDoesntHave('roles', fn ($query) => $query->whereIn('name', ['admin', 'super_admin']))
            ->pluck('id');

        $this->locations = Location::query()
            ->with(['departments', 'rooms'])
            ->get()
            ->filter(fn (Location $location): bool => $location->departments->isNotEmpty() && $location->rooms->isNotEmpty())
            ->values();

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
                'purchase_price' => 13_500_000,
                'notes' => 'Seri iPhone 14, layar Super Retina XDR 6.1", chip A15 Bionic, warna umum Midnight/Starlight',
            ],
            [
                'name' => 'iPhone 14 Pro 256GB',
                'model_number' => 'AAPL-IPH14P-256',
                'min_amount' => 1,
                'end_of_life' => 36,
                'audit_interval' => 12,
                'purchase_price' => 17_500_000,
                'notes' => 'Seri iPhone 14 Pro, layar 6.1" ProMotion, chip A16 Bionic, kamera 48 MP',
            ],
            [
                'name' => 'iPhone 15 128GB',
                'model_number' => 'AAPL-IPH15-128',
                'min_amount' => 1,
                'end_of_life' => 36,
                'audit_interval' => 12,
                'purchase_price' => 14_500_000,
                'notes' => 'Seri iPhone 15, USB-C, Dynamic Island, chip A16 Bionic',
            ],
            [
                'name' => 'iPhone 15 Pro 256GB',
                'model_number' => 'AAPL-IPH15P-256',
                'min_amount' => 1,
                'end_of_life' => 36,
                'audit_interval' => 12,
                'purchase_price' => 21_000_000,
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
                'purchase_price' => 16_500_000,
                'notes' => 'MacBook Air 13 inci, Apple M2, RAM 8 GB unified, SSD 256 GB',
            ],
            [
                'name' => 'MacBook Air 15" M2 512GB',
                'model_number' => 'AAPL-MBA15-M2-512',
                'min_amount' => 1,
                'end_of_life' => 48,
                'audit_interval' => 12,
                'purchase_price' => 21_500_000,
                'notes' => 'MacBook Air 15 inci, Apple M2, layar Liquid Retina lebih besar',
            ],
            [
                'name' => 'MacBook Pro 14" M3 512GB',
                'model_number' => 'AAPL-MBP14-M3-512',
                'min_amount' => 1,
                'end_of_life' => 60,
                'audit_interval' => 12,
                'purchase_price' => 27_500_000,
                'notes' => 'MacBook Pro 14 inci, chip Apple M3, cocok untuk desain ringan dan administrasi',
            ],
            [
                'name' => 'MacBook Pro 16" M3 Pro 512GB',
                'model_number' => 'AAPL-MBP16-M3PRO-512',
                'min_amount' => 1,
                'end_of_life' => 60,
                'audit_interval' => 12,
                'purchase_price' => 38_000_000,
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
                'purchase_price' => 1_200_000,
                'notes' => 'Sandal karet motif batik, sol anti slip, ukuran campur',
            ],
            [
                'name' => 'sandal polos',
                'model_number' => 'SWL-POLOS-001',
                'min_amount' => 2,
                'end_of_life' => 24,
                'audit_interval' => 12,
                'purchase_price' => 1_200_000,
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
                'purchase_price' => 1_000_000,
                'notes' => 'HVS A4 80 gsm, isi ±500 lembar per rim, putih standar fotokopi',
            ],
            [
                'name' => 'Kertas F4',
                'model_number' => 'SIDU-F4-80-500',
                'min_amount' => 10,
                'end_of_life' => null,
                'audit_interval' => 6,
                'purchase_price' => 1_100_000,
                'notes' => 'HVS F4/Folio 80 gsm, isi ±500 lembar per rim, untuk dokumen legal Indonesia',
            ],
        ];

        foreach ($smartphoneModels as $modelData) {
            $model = $this->upsertInventoryModel($apple, $smartphone, $modelData, $iphoneDepreciation?->id);
            $this->seedTrackedAssetItems($model, $modelData, $smartPhoneLaptopSupplier?->id);
        }

        foreach ($laptopModels as $modelData) {
            $model = $this->upsertInventoryModel($apple, $laptop, $modelData, $macbookDepreciation?->id);
            $this->seedTrackedAssetItems($model, $modelData, $smartPhoneLaptopSupplier?->id);
        }

        foreach ($sandalModels as $modelData) {
            $model = $this->upsertInventoryModel($swallow, $sandal, $modelData);
            $this->seedBulkItems($model, $modelData, $sandalSupplier?->id);
        }

        foreach ($kertasModels as $modelData) {
            $model = $this->upsertInventoryModel($sidu, $kertas, $modelData);
            $this->seedBulkItems($model, $modelData, $kertasSupplier?->id);
        }

        $this->seedStockMovementsForBulkItems();
        $this->seedItemAuditsForAllItems();
        $this->seedMaintenanceForTrackedItems();
        $this->seedItemStateLogsForTrackedItems();
    }

    /**
     * @param  array{name: string, model_number: string, min_amount: int, end_of_life: int|null, audit_interval: int, purchase_price: int, notes: string}  $modelData
     */
    private function upsertInventoryModel(
        Manufacture $manufacture,
        Category $category,
        array $modelData,
        ?string $depreciationId = null,
    ): InventoryModel {
        return InventoryModel::updateOrCreate(
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

    /**
     * @return array{location_id: string, department_id: string, room_id: string}|null
     */
    private function randomPlacement(): ?array
    {
        if ($this->locations->isEmpty()) {
            return null;
        }

        $location = $this->locations->random();
        $department = $location->departments->random();
        $room = $location->rooms->random();

        return [
            'location_id' => $location->id,
            'department_id' => $department->id,
            'room_id' => $room->id,
        ];
    }

    private function randomPurchaseDateIn2025(): Carbon
    {
        $month = random_int(1, 12);
        $day = random_int(1, Carbon::create(2025, $month)->daysInMonth);

        return Carbon::create(2025, $month, $day)->startOfDay();
    }

    private function randomEligibleUserId(): ?string
    {
        if ($this->eligibleUserIds->isEmpty()) {
            return null;
        }

        return $this->eligibleUserIds->random();
    }

    private function canSeedItems(): bool
    {
        if ($this->locations->isEmpty()) {
            $this->command?->warn('ModelSeeder: skipping item seeding — no locations with departments and rooms.');

            return false;
        }

        return true;
    }

    /**
     * @param  array{name: string, model_number: string, min_amount: int, end_of_life: int|null, audit_interval: int, purchase_price: int, notes: string}  $modelData
     */
    private function seedTrackedAssetItems(
        InventoryModel $model,
        array $modelData,
        ?string $supplierId,
        int $count = 3,
    ): void {
        if (! $this->canSeedItems()) {
            return;
        }

        $existingCount = $model->items()->count();

        if ($existingCount >= $count) {
            return;
        }

        for ($i = $existingCount; $i < $count; $i++) {
            $placement = $this->randomPlacement();

            if ($placement === null) {
                return;
            }

            $purchaseDate = $this->randomPurchaseDateIn2025();
            $eolDate = $model->end_of_life !== null
                ? $purchaseDate->copy()->addMonths((int) $model->end_of_life)
                : null;

            Item::create([
                'model_id' => $model->id,
                'location_id' => $placement['location_id'],
                'department_id' => $placement['department_id'],
                'room_id' => $placement['room_id'],
                'supplier_id' => $supplierId,
                'user_id' => $this->randomEligibleUserId(),
                'name' => null,
                'serial_number' => ItemSerialNumber::generate(),
                'quantity' => 1,
                'purchase_date' => $purchaseDate,
                'purchase_price' => $modelData['purchase_price'],
                'eol_date' => $eolDate,
                'warranty_months' => null,
                'is_individual_tracking' => true,
                'status' => ItemStatus::Active,
                'notes' => null,
                'status_updated_at' => null,
            ]);
        }
    }

    /**
     * @param  array{name: string, model_number: string, min_amount: int, end_of_life: int|null, audit_interval: int, purchase_price: int, notes: string}  $modelData
     */
    private function seedBulkItems(
        InventoryModel $model,
        array $modelData,
        ?string $supplierId,
        int $quantity = 20,
    ): void {
        if (! $this->canSeedItems()) {
            return;
        }

        if ($model->items()->exists()) {
            return;
        }

        $placement = $this->randomPlacement();

        if ($placement === null) {
            return;
        }

        $purchaseDate = $this->randomPurchaseDateIn2025();

        $item = Item::create([
            'model_id' => $model->id,
            'location_id' => $placement['location_id'],
            'department_id' => $placement['department_id'],
            'room_id' => $placement['room_id'],
            'supplier_id' => $supplierId,
            'user_id' => $this->randomEligibleUserId(),
            'name' => null,
            'serial_number' => ItemSerialNumber::generate(),
            'quantity' => $quantity,
            'purchase_date' => $purchaseDate,
            'purchase_price' => $modelData['purchase_price'],
            'eol_date' => null,
            'warranty_months' => null,
            'is_individual_tracking' => false,
            'status' => ItemStatus::Active,
            'notes' => null,
            'status_updated_at' => null,
        ]);
    }

    private function seedStockMovementsForBulkItems(): void
    {
        Item::query()
            ->where('is_individual_tracking', false)
            ->each(fn (Item $item) => $this->seedStockMovementsForItem($item));
    }

    private function seedStockMovementsForItem(Item $item): void
    {
        $existingCount = $item->stockMovements()->count();

        if ($existingCount >= 10) {
            return;
        }

        $initialQty = (int) $item->quantity;
        $movements = $this->stockMovementSequence($initialQty);

        if ($existingCount === 1) {
            $movements = array_slice($movements, 1);
        }

        foreach ($movements as $movement) {
            if (! $item->canApplyStockMovement($movement['quantity'])) {
                continue;
            }

            StockMovement::create([
                'item_id' => $item->id,
                'type' => $movement['type'],
                'quantity' => $movement['quantity'],
                'notes' => $movement['notes'],
            ]);

            $item->refresh();
        }
    }

    /**
     * @return list<array{type: StockMovementType, quantity: int, notes: string|null}>
     */
    private function stockMovementSequence(int $initialQty): array
    {
        return [
            [
                'type' => StockMovementType::In,
                'quantity' => $initialQty,
                'notes' => __('items.create.initial_stock_notes'),
            ],
            [
                'type' => StockMovementType::Out,
                'quantity' => -3,
                'notes' => 'Pengeluaran stok operasional',
            ],
            [
                'type' => StockMovementType::Out,
                'quantity' => -2,
                'notes' => 'Pengeluaran stok operasional',
            ],
            [
                'type' => StockMovementType::Adjustment,
                'quantity' => -2,
                'notes' => 'Penyesuaian stok setelah audit',
            ],
            [
                'type' => StockMovementType::In,
                'quantity' => 7,
                'notes' => 'Restock dari supplier',
            ],
            [
                'type' => StockMovementType::Out,
                'quantity' => -4,
                'notes' => 'Pengeluaran stok operasional',
            ],
            [
                'type' => StockMovementType::In,
                'quantity' => 2,
                'notes' => 'Restock dari supplier',
            ],
            [
                'type' => StockMovementType::Adjustment,
                'quantity' => 2,
                'notes' => 'Penyesuaian stok setelah audit',
            ],
            [
                'type' => StockMovementType::Out,
                'quantity' => -3,
                'notes' => 'Pengeluaran stok operasional',
            ],
            [
                'type' => StockMovementType::In,
                'quantity' => 3,
                'notes' => 'Restock dari supplier',
            ],
        ];
    }

    private function seedItemAuditsForAllItems(): void
    {
        $auditSchedules = [
            [
                'audited_at' => Carbon::create(2025, 1, 1, 9, 0, 0),
                'condition' => ItemAuditCondition::Good,
                'result' => ItemAuditResult::Ok,
            ],
            [
                'audited_at' => Carbon::create(2025, 7, 1, 9, 0, 0),
                'condition' => ItemAuditCondition::Fair,
                'result' => ItemAuditResult::NeedsMaintenance,
            ],
            [
                'audited_at' => Carbon::create(2026, 1, 1, 9, 0, 0),
                'condition' => ItemAuditCondition::Good,
                'result' => ItemAuditResult::Ok,
            ],
        ];

        Item::query()
            ->with('model')
            ->each(function (Item $item) use ($auditSchedules): void {
                if ($item->audits()->count() >= 3) {
                    return;
                }

                foreach ($auditSchedules as $schedule) {
                    $auditedAt = $schedule['audited_at']->copy();

                    ItemAudit::create([
                        'item_id' => $item->id,
                        'location_verified' => true,
                        'audited_at' => $auditedAt,
                        'next_audit_at' => $auditedAt->copy()->addMonths((int) $item->model->audit_interval),
                        'condition' => $schedule['condition'],
                        'result' => $schedule['result'],
                        'notes' => null,
                    ]);
                }
            });
    }

    private function seedMaintenanceForTrackedItems(): void
    {
        $maintenanceTypes = [
            MaintenanceType::Preventive,
            MaintenanceType::Repair,
            MaintenanceType::Upgrade,
            MaintenanceType::Inspection,
        ];

        $costsByType = [
            MaintenanceType::Preventive->value => 500_000,
            MaintenanceType::Repair->value => 2_500_000,
            MaintenanceType::Upgrade->value => 5_000_000,
            MaintenanceType::Inspection->value => 750_000,
        ];

        Item::query()
            ->where('is_individual_tracking', true)
            ->each(function (Item $item) use ($maintenanceTypes, $costsByType): void {
                if ($item->maintenances()->count() >= 4) {
                    return;
                }

                $base = $item->purchase_date?->copy()->startOfDay() ?? Carbon::create(2025, 6, 1);

                foreach ($maintenanceTypes as $index => $type) {
                    $reportedAt = $base->copy()->addDays($index * 18);
                    $startedAt = $reportedAt->copy()->addDay();
                    $completedAt = $startedAt->copy()->addDays(3);

                    Maintenance::create([
                        'item_id' => $item->id,
                        'type' => $type,
                        'reported_at' => $reportedAt,
                        'started_at' => $startedAt,
                        'completed_at' => $completedAt,
                        'item_audit_id' => null,
                        'status' => MaintenanceStatus::Completed,
                        'cost' => $costsByType[$type->value],
                        'notes' => null,
                    ]);
                }
            });
    }

    private function seedItemStateLogsForTrackedItems(): void
    {
        Item::query()
            ->where('is_individual_tracking', true)
            ->each(fn (Item $item) => $this->seedItemStateLogsForItem($item));
    }

    private function seedItemStateLogsForItem(Item $item): void
    {
        if ($item->stateLogs()->count() >= 3) {
            return;
        }

        $transferPlacement = $this->randomDifferentPlacement($item);

        if ($transferPlacement !== null) {
            ItemStateLog::create([
                'item_id' => $item->id,
                'item_audit_id' => null,
                'maintenance_id' => null,
                'event_type' => ItemStateEventType::Transfer,
                'from_location_id' => $item->location_id,
                'to_location_id' => $transferPlacement['location_id'],
                'from_department_id' => $item->department_id,
                'to_department_id' => $transferPlacement['department_id'],
                'from_room_id' => $item->room_id,
                'to_room_id' => $transferPlacement['room_id'],
                'notes' => null,
            ]);

            $item->refresh();
        }

        ItemStateLog::create([
            'item_id' => $item->id,
            'item_audit_id' => null,
            'maintenance_id' => null,
            'event_type' => ItemStateEventType::Assignment,
            'from_user_id' => $item->user_id,
            'to_user_id' => $this->randomDifferentEligibleUserId($item->user_id),
            'notes' => null,
        ]);

        $item->refresh();

        ItemStateLog::create([
            'item_id' => $item->id,
            'item_audit_id' => null,
            'maintenance_id' => null,
            'event_type' => ItemStateEventType::StatusChange,
            'from_status' => ItemStatus::UnderRepair,
            'to_status' => ItemStatus::Active,
            'notes' => null,
        ]);
    }

    /**
     * @return array{location_id: string, department_id: string, room_id: string}|null
     */
    private function randomDifferentPlacement(Item $item): ?array
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $placement = $this->randomPlacement();

            if ($placement === null) {
                return null;
            }

            if ($placement['location_id'] !== $item->location_id
                || $placement['department_id'] !== $item->department_id
                || $placement['room_id'] !== $item->room_id) {
                return $placement;
            }
        }

        return $this->randomPlacement();
    }

    private function randomDifferentEligibleUserId(?string $currentUserId): ?string
    {
        if ($this->eligibleUserIds->isEmpty()) {
            return null;
        }

        $candidates = $this->eligibleUserIds->filter(
            fn (string $id): bool => $id !== $currentUserId,
        );

        if ($candidates->isEmpty()) {
            return $this->eligibleUserIds->random();
        }

        return $candidates->random();
    }
}

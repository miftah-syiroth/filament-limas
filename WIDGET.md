# Dashboard Widget Plan — SIRIS

Dokumen spesifikasi implementasi widget dashboard. Setiap item mencakup: deskripsi, tabel, relasi, query, kolom tipe/status, agregat, dan jenis widget Filament yang disarankan.

**Konvensi scope inventori**

- `Item::inInventory()` — mengecualikan status `lost`, `stolen`, `archived`, `disposed` (`ItemStatus::excludedFromInventory()`).
- Item **aktif** — `status = ItemStatus::Active`.
- Nilai moneter diformat IDR, tanpa desimal.

**Jenis widget Filament**

| Jenis | Paket | Kapan dipakai |
|---|---|---|
| `StatsOverviewWidget` | `filament/widgets` | Angka tunggal / KPI card |
| `ChartWidget` (doughnut / bar / line) | `filament/widgets` | Distribusi & tren |
| `TableWidget` | `filament/widgets` | Daftar ringkas dengan link ke resource |

---

## Stats Overview (baris alert / KPI atas)

### 1. Maintenance belum selesai

| | |
|---|---|
| **Deskripsi** | Jumlah tiket maintenance yang masih terbuka dan belum diselesaikan maupun dibatalkan. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, warna `warning`, link ke `MaintenanceResource` dengan filter status. |
| **Tabel** | `maintenances` |
| **Model** | `Maintenance` |
| **Relasi** | `item` → `Item` (`belongsTo`) |
| **Kolom status** | `status` — enum `MaintenanceStatus`: `reported`, `in_progress`, `completed`, `cancelled` |
| **Kolom tipe** | `type` — enum `MaintenanceType`: `preventive`, `repair`, `upgrade`, `inspection` |
| **Kolom tanggal** | `reported_at`, `started_at`, `completed_at` (datetime) |
| **Query** | `Maintenance::query()->whereIn('status', [MaintenanceStatus::Reported, MaintenanceStatus::InProgress])` |
| **Agregat** | `COUNT(*)` |
| **Catatan** | Card bisa dipecah jadi 2 sub-label (`reported` vs `in_progress`) jika ingin detail tanpa widget terpisah. |

---

### 2. Audit jatuh tempo

| | |
|---|---|
| **Deskripsi** | Item yang jadwal auditnya sudah lewat atau akan jatuh tempo dalam 7 hari ke depan. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, warna `danger` jika ada yang overdue, `warning` jika hanya mendekati. Link ke `ItemResource` / halaman audit. |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | `model` → `Model` (`belongsTo`, punya `audit_interval`); `audits` → `ItemAudit` (`hasMany`) |
| **Kolom status** | `status` — enum `ItemStatus` |
| **Kolom audit** | `last_audit_date`, `next_audit_date` (datetime, di-update otomatis saat `ItemAudit` dibuat) |
| **Query** | `Item::query()->inInventory()->whereNotNull('next_audit_date')->where('next_audit_date', '<=', now()->addDays(7))` |
| **Agregat** | `COUNT(*)` |
| **Catatan** | Overdue murni: ganti kondisi jadi `next_audit_date < now()`. Bisa tampilkan sub-count overdue vs mendatang dalam deskripsi card. |

---

### 3. Stok di bawah minimum

| | |
|---|---|
| **Deskripsi** | Jumlah **model** (bukan item individual) yang total stok inventori-nya di bawah `min_amount` yang ditetapkan di master model. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, warna `danger`, link ke `ModelResource` atau custom filtered list. |
| **Tabel** | `models`, `items` |
| **Model** | `Model`, `Item` |
| **Relasi** | `Model::itemsInInventory()` — `hasMany Item` + scope `inInventory()` |
| **Kolom tipe** | — (agregasi per model) |
| **Kolom status item** | `items.status` — enum `ItemStatus`, difilter via `inInventory()` |
| **Kolom threshold** | `models.min_amount` (integer, nullable) |
| **Kolom kuantitas** | `items.quantity` (integer) |
| **Query** | `Model::query()->whereNotNull('min_amount')->whereHas('itemsInInventory')->withSum('itemsInInventory as total_quantity', 'quantity')->havingRaw('COALESCE(total_quantity, 0) < min_amount')` — atau subquery `GROUP BY model_id` |
| **Agregat** | `COUNT(models.id)` — model yang stok totalnya < `min_amount` |
| **Catatan** | Hanya model dengan `min_amount` terisi. Consumable & non-individual tracking paling relevan. |

---

### 4. Item bermasalah

| | |
|---|---|
| **Deskripsi** | Item dengan kondisi operasional bermasalah: rusak, sedang diagnosis/perbaikan, atau tidak bisa diperbaiki. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, warna `warning`, link ke `ItemResource` filter status. |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | `model`, `location`, `department` (opsional untuk drill-down) |
| **Kolom status** | `status` — enum `ItemStatus`: filter `damaged`, `under_repair`, `under_diagnosis`, `irreparable` |
| **Query** | `Item::query()->whereIn('status', [ItemStatus::Damaged, ItemStatus::UnderRepair, ItemStatus::UnderDiagnosis, ItemStatus::Irreparable])` |
| **Agregat** | `COUNT(*)` — atau `SUM(quantity)` jika ingin unit fisik |
| **Catatan** | Tidak memakai `inInventory()` karena status bermasalah mungkin masih perlu dilacak meski bukan `active`. |

---

### 5. Item hilang / dicuri

| | |
|---|---|
| **Deskripsi** | Item berstatus hilang atau dicuri — termasuk dalam `excludedFromInventory()`. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, warna `danger`, link ke `ItemResource`. |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | `model`, `location`, `user` |
| **Kolom status** | `status` — enum `ItemStatus`: `lost`, `stolen` |
| **Query** | `Item::query()->whereIn('status', [ItemStatus::Lost, ItemStatus::Stolen])` |
| **Agregat** | `COUNT(*)` |
| **Catatan** | Bisa pecah sub-count `lost` vs `stolen` di description card. |

---

## Inventory

### 1. Total item

| | |
|---|---|
| **Deskripsi** | Ringkasan volume inventori yang masih dilacak sistem. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, bisa 2 baris stat dalam 1 widget. |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | — |
| **Kolom status** | `status` — difilter `inInventory()` |
| **Kolom kuantitas** | `quantity` (integer); `is_individual_tracking` (boolean) |
| **Query** | Base: `Item::query()->inInventory()` |
| **Agregat** | **Utama:** `SUM(quantity)` — total unit fisik. **Sekunder:** `COUNT(*)` — jumlah baris item / aset terdaftar. |
| **Catatan** | Tampilkan keduanya: "X unit" (besar) + "Y item terdaftar" (kecil). Individual tracking biasanya `quantity = 1`. |

---

### 2. Total nilai aset

| | |
|---|---|
| **Deskripsi** | Total harga pembelian semua item yang masih dalam inventori. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, format money IDR. |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | — |
| **Kolom** | `purchase_price` (decimal:2), `purchase_date` (datetime) |
| **Kolom status** | `status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->whereNotNull('purchase_price')` |
| **Agregat** | `SUM(purchase_price)` |
| **Catatan** | Item tanpa `purchase_price` diabaikan. Untuk consumable dengan qty > 1, `purchase_price` diasumsikan per baris item (bukan per unit) — sesuaikan jika bisnis rules berbeda. |

---

### 3. Nilai buku saat ini

| | |
|---|---|
| **Deskripsi** | Total nilai aset setelah penyusutan, dihitung per item via accessor `depreciated_price`. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, format money IDR. |
| **Tabel** | `items`, `models`, `depreciations` |
| **Model** | `Item` |
| **Relasi** | `model.depreciation` → `Depreciation` (`belongsTo` via `Model`) |
| **Kolom** | `purchase_price`, `purchase_date`; `depreciations.months`, `depreciations.minimum_value` (persen) |
| **Kolom status** | `status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->whereNotNull('purchase_price')->whereNotNull('purchase_date')->whereHas('model.depreciation')` — load collection, jumlahkan `$item->depreciated_price` di PHP |
| **Agregat** | `SUM(depreciated_price)` — **computed di aplikasi**, bukan kolom DB |
| **Catatan** | Tidak bisa `SUM()` langsung di SQL. Pertimbangkan cache harian jika dataset besar. Item tanpa skema depresiasi di-skip. |

---

### 4. Item per status

| | |
|---|---|
| **Deskripsi** | Distribusi item inventori berdasarkan status operasional. |
| **Widget** | `ChartWidget` — **doughnut** (`DoughnutChartWidget` / `type: 'doughnut'`). |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | — |
| **Kolom status** | `status` — enum `ItemStatus` (semua case kecuali yang dikecualikan jika pakai `inInventory()`) |
| **Query** | `Item::query()->inInventory()->selectRaw('status, COUNT(*) as total')->groupBy('status')` |
| **Agregat** | `COUNT(*)` per `status`; label pakai `ItemStatus::getLabel()` |
| **Catatan** | Warna slice mengikuti badge color convention Filament / enum. Klik slice → filter `ItemResource`. |

---

### 5. Item per kategori

| | |
|---|---|
| **Deskripsi** | Distribusi item berdasarkan kategori master (nama kategori atau tipe kategori). |
| **Widget** | `ChartWidget` — **doughnut** (≤6 kategori) atau **bar** (banyak kategori). |
| **Tabel** | `items`, `models`, `categories` |
| **Model** | `Item` |
| **Relasi** | `model.category` → `Category` (`Item` → `Model` → `Category`) |
| **Kolom tipe** | `categories.type` — enum `CategoryType`: `asset`, `accessory`, `consumable`, `license` |
| **Kolom label** | `categories.name` |
| **Kolom status** | `items.status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->join('models', ...)->join('categories', ...)->groupBy('categories.id', 'categories.name')->selectRaw('categories.name, COUNT(items.id) as total')` — atau `with('model.category')` + collection group |
| **Agregat** | `COUNT(items.id)` per `categories.name` (atau per `categories.type` jika ingin 4 slice tetap) |
| **Catatan** | Pilih salah satu dimensi: **nama kategori** (detail) atau **tipe kategori** (4 warna tetap, selaras `CategoryType::getColor()`). |

---

### 6. Item per lokasi

| | |
|---|---|
| **Deskripsi** | Distribusi item inventori per lokasi fisik. |
| **Widget** | `ChartWidget` — **bar** horizontal (banyak lokasi) atau **doughnut** (top 5 + "Lainnya"). |
| **Tabel** | `items`, `locations` |
| **Model** | `Item` |
| **Relasi** | `location` → `Location` (`belongsTo`) |
| **Kolom** | `locations.name` |
| **Kolom status** | `items.status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->whereNotNull('location_id')->with('location')->get()->groupBy('location.name')` atau SQL `GROUP BY location_id` |
| **Agregat** | `COUNT(*)` atau `SUM(quantity)` per lokasi |
| **Catatan** | Item tanpa `location_id` masuk bucket "Tanpa lokasi" terpisah. |

---

### 7. Item per departemen

| | |
|---|---|
| **Deskripsi** | Distribusi item inventori per departemen penanggung jawab. |
| **Widget** | `ChartWidget` — **bar** horizontal atau **doughnut** (top 5). |
| **Tabel** | `items`, `departments` |
| **Model** | `Item` |
| **Relasi** | `department` → `Department` (`belongsTo`) |
| **Kolom** | `departments.name` |
| **Kolom status** | `items.status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->whereNotNull('department_id')->...` — sama pola dengan lokasi |
| **Agregat** | `COUNT(*)` atau `SUM(quantity)` per departemen |
| **Catatan** | Item tanpa departemen → bucket "Tanpa departemen". |

---

### 8. Aset mendekati EOL

| | |
|---|---|
| **Deskripsi** | Item yang tanggal akhir hidup (`eol_date`) akan tiba dalam 90 hari (atau tier 30/60/90). |
| **Widget** | **Kombinasi:** `StatsOverviewWidget` (count total mendekati EOL) + `TableWidget` (5–10 item terdekat). |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | `model` → `Model` (`end_of_life` dalam bulan, referensi alternatif jika `eol_date` null) |
| **Kolom** | `eol_date` (datetime) — sumber utama; fallback: `purchase_date` + `models.end_of_life` bulan |
| **Kolom status** | `status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->whereNotNull('eol_date')->whereBetween('eol_date', [now(), now()->addDays(90)])->orderBy('eol_date')` |
| **Agregat** | Card: `COUNT(*)`. Tabel: kolom `serial_number`, `model.name`, `eol_date`, `location.name`. |
| **Catatan** | Tier warna card: ≤30 hari `danger`, ≤60 `warning`, ≤90 `info`. Jika `eol_date` sering kosong, pertimbangkan hitung dari `purchase_date + model.end_of_life months`. |

---

### 9. Garansi hampir habis

| | |
|---|---|
| **Deskripsi** | Item yang masa garansinya berakhir dalam 30 hari ke depan. |
| **Widget** | **Kombinasi:** `StatsOverviewWidget` (count) + `TableWidget` (daftar singkat, opsional). |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | `model`, `supplier` (opsional) |
| **Kolom** | `purchase_date` (datetime), `warranty_months` (integer) |
| **Kolom status** | `status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->whereNotNull('purchase_date')->whereNotNull('warranty_months')->whereRaw('DATE_ADD(purchase_date, INTERVAL warranty_months MONTH) BETWEEN ? AND ?', [now(), now()->addDays(30)])` — atau `whereDate` di PHP/Carbon |
| **Agregat** | `COUNT(*)` |
| **Kolom tabel (jika ada)** | `serial_number`, `model.name`, tanggal akhir garansi (computed: `purchase_date->addMonths(warranty_months)`), `supplier.name` |
| **Catatan** | Garansi sudah lewat bisa masuk bucket terpisah (overdue warranty) jika diperlukan. |

---

## Audit & Maintenance

### 1. Audit bulan ini

| | |
|---|---|
| **Deskripsi** | Jumlah audit fisik yang dilakukan pada bulan kalender berjalan. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, link ke `ItemAuditResource`. |
| **Tabel** | `item_audits` |
| **Model** | `ItemAudit` |
| **Relasi** | `item` → `Item` (`belongsTo`) |
| **Kolom tanggal** | `audited_at` (datetime) |
| **Kolom hasil** | `result` — enum `ItemAuditResult`: `ok`, `needs_maintenance`, `needs_replacement`, `dispose` |
| **Kolom kondisi** | `condition` — enum `ItemAuditCondition` |
| **Query** | `ItemAudit::query()->whereBetween('audited_at', [now()->startOfMonth(), now()->endOfMonth()])` |
| **Agregat** | `COUNT(*)` |
| **Catatan** | Bisa tambah perbandingan vs bulan lalu (delta %) di description card. |

---

### 2. Hasil audit bermasalah

| | |
|---|---|
| **Deskripsi** | Audit dengan hasil yang memerlukan tindak lanjut (bukan `ok`). |
| **Widget** | `StatsOverviewWidget` — 1 stat card, warna `warning`. Bisa tambah `ChartWidget` doughnut per `result`. |
| **Tabel** | `item_audits` |
| **Model** | `ItemAudit` |
| **Relasi** | `item` → `Item` |
| **Kolom hasil** | `result` — enum `ItemAuditResult`: filter `needs_maintenance`, `needs_replacement`, `dispose` |
| **Query** | `ItemAudit::query()->whereIn('result', [ItemAuditResult::NeedsMaintenance, ItemAuditResult::NeedsReplacement, ItemAuditResult::Dispose])` — scope waktu opsional: bulan ini / 30 hari terakhir |
| **Agregat** | `COUNT(*)` — atau `COUNT(*)` per `result` untuk chart |
| **Catatan** | Untuk trend, filter `audited_at >= now()->subDays(30)`. |

---

### 3. Lokasi tidak terverifikasi

| | |
|---|---|
| **Deskripsi** | Audit di mana lokasi fisik item tidak cocok / belum diverifikasi. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, warna `danger`. |
| **Tabel** | `item_audits` |
| **Model** | `ItemAudit` |
| **Relasi** | `item.location` |
| **Kolom** | `location_verified` (boolean) |
| **Kolom hasil** | `result` — enum `ItemAuditResult` |
| **Query** | `ItemAudit::query()->where('location_verified', false)` — scope waktu opsional |
| **Agregat** | `COUNT(*)` |
| **Catatan** | Drill-down ke `ItemAuditResource` filter `location_verified = false`. |

---

### 4. Biaya maintenance bulan ini

| | |
|---|---|
| **Deskripsi** | Total biaya maintenance yang sudah selesai pada bulan berjalan. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, format money IDR. |
| **Tabel** | `maintenances` |
| **Model** | `Maintenance` |
| **Relasi** | `item` → `Item` |
| **Kolom** | `cost` (decimal:2), `completed_at` (datetime) |
| **Kolom status** | `status` — enum `MaintenanceStatus`: filter `completed` |
| **Query** | `Maintenance::query()->where('status', MaintenanceStatus::Completed)->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()])->whereNotNull('cost')` |
| **Agregat** | `SUM(cost)` |
| **Catatan** | Maintenance `in_progress` dengan estimasi biaya tidak dihitung (field estimasi belum ada). |

---

### 5. Maintenance per tipe

| | |
|---|---|
| **Deskripsi** | Distribusi tiket maintenance berdasarkan jenis pekerjaan. |
| **Widget** | `ChartWidget` — **doughnut** atau **bar**. |
| **Tabel** | `maintenances` |
| **Model** | `Maintenance` |
| **Relasi** | `item` → `Item` |
| **Kolom tipe** | `type` — enum `MaintenanceType`: `preventive`, `repair`, `upgrade`, `inspection` |
| **Kolom status** | `status` — enum `MaintenanceStatus` (scope: semua atau hanya `completed`) |
| **Query** | `Maintenance::query()->selectRaw('type, COUNT(*) as total')->groupBy('type')` — filter periode opsional via `reported_at` |
| **Agregat** | `COUNT(*)` per `type`; label pakai `MaintenanceType::getLabel()` |
| **Catatan** | Default scope: 12 bulan terakhir agar chart relevan. |

---

### 6. Item tanpa audit

| | |
|---|---|
| **Deskripsi** | Item yang seharusnya diaudit (model punya `audit_interval`) tetapi belum pernah diaudit. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, link ke filtered `ItemResource`. |
| **Tabel** | `items`, `models` |
| **Model** | `Item` |
| **Relasi** | `model` → `Model` (`audit_interval` integer, dalam bulan) |
| **Kolom** | `last_audit_date` (nullable datetime), `next_audit_date` (nullable datetime) |
| **Kolom status** | `status` — via `inInventory()` |
| **Query** | `Item::query()->inInventory()->whereNull('last_audit_date')->whereHas('model', fn ($q) => $q->whereNotNull('audit_interval')->where('audit_interval', '>', 0))` |
| **Agregat** | `COUNT(*)` |
| **Catatan** | Berbeda dari widget #2 Stats Overview (audit jatuh tempo): ini khusus item yang **belum pernah** diaudit sama sekali. |

---

## Stock

### 1. Pergerakan stok hari ini

| | |
|---|---|
| **Deskripsi** | Ringkasan transaksi stok masuk, keluar, dan penyesuaian yang tercatat hari ini. |
| **Widget** | `StatsOverviewWidget` — 3 stat dalam 1 widget (`In`, `Out`, `Adjustment`) atau 1 card total + breakdown. |
| **Tabel** | `stock_movements` |
| **Model** | `StockMovement` |
| **Relasi** | `item` → `Item` → `model.category` |
| **Kolom tipe** | `type` — enum `StockMovementType`: `in`, `out`, `adjustment` |
| **Kolom kuantitas** | `quantity` (integer, bisa negatif untuk `out`) |
| **Query** | `StockMovement::query()->whereDate('created_at', today())` — grup per `type` |
| **Agregat** | `COUNT(*)` per tipe; opsional `SUM(ABS(quantity))` per tipe untuk volume unit |
| **Catatan** | Hanya relevan untuk item `is_individual_tracking = false` (consumable/stok massal). Filter opsional: `whereHas('item', fn ($q) => $q->where('is_individual_tracking', false))`. |

---

### 2. Consumable habis / kritis

| | |
|---|---|
| **Deskripsi** | Model kategori consumable yang stok totalnya di bawah atau sama dengan `min_amount`. |
| **Widget** | `StatsOverviewWidget` (count model kritis) + `TableWidget` (daftar model + stok vs minimum). |
| **Tabel** | `models`, `categories`, `items` |
| **Model** | `Model`, `Item`, `Category` |
| **Relasi** | `Model::category` → `Category`; `Model::itemsInInventory()` |
| **Kolom tipe** | `categories.type` — enum `CategoryType::Consumable` |
| **Kolom** | `models.min_amount`, `models.name`; agregat `SUM(items.quantity)` |
| **Kolom status item** | via `itemsInInventory()` |
| **Query** | `Model::query()->whereHas('category', fn ($q) => $q->where('type', CategoryType::Consumable))->whereNotNull('min_amount')->withSum('itemsInInventory as total_quantity', 'quantity')->havingRaw('COALESCE(total_quantity, 0) <= min_amount')` |
| **Agregat** | Card: `COUNT(models)`. Tabel: `name`, `total_quantity`, `min_amount`, selisih. |
| **Catatan** | Mirip Stats Overview #3 tetapi difilter `CategoryType::Consumable` saja. Kritis = `<= min_amount`; habis = `= 0`. |

---

### 3. Top consumable terpakai

| | |
|---|---|
| **Deskripsi** | Consumable dengan volume keluar stok terbanyak dalam periode tertentu (default: 30 hari). |
| **Widget** | `ChartWidget` — **bar** horizontal (top 10) atau `TableWidget`. |
| **Tabel** | `stock_movements`, `items`, `models`, `categories` |
| **Model** | `StockMovement` |
| **Relasi** | `item.model.category` |
| **Kolom tipe movement** | `type` — enum `StockMovementType::Out` |
| **Kolom tipe kategori** | `categories.type` — `CategoryType::Consumable` |
| **Kolom kuantitas** | `quantity` |
| **Query** | `StockMovement::query()->where('type', StockMovementType::Out)->where('created_at', '>=', now()->subDays(30))->whereHas('item.model.category', fn ($q) => $q->where('type', CategoryType::Consumable))->selectRaw('item_id, SUM(ABS(quantity)) as total_out')->groupBy('item_id')->orderByDesc('total_out')->limit(10)` — join ke `models.name` via `items.model_id` |
| **Agregat** | `SUM(ABS(quantity))` per `item_id` atau per `model_id` (lebih disarankan per model) |
| **Catatan** | Group by `models.id` / `models.name` agar beberapa baris item model sama digabung. Label chart: nama model. |

---

## Finance & Depresiasi

### 1. Total nilai pembelian

| | |
|---|---|
| **Deskripsi** | Total harga beli semua item berstatus aktif. |
| **Widget** | `StatsOverviewWidget` — 1 stat card, format money IDR. |
| **Tabel** | `items` |
| **Model** | `Item` |
| **Relasi** | — |
| **Kolom status** | `status` — enum `ItemStatus::Active` |
| **Kolom** | `purchase_price` (decimal:2) |
| **Query** | `Item::query()->where('status', ItemStatus::Active)->whereNotNull('purchase_price')` |
| **Agregat** | `SUM(purchase_price)` |
| **Catatan** | Berbeda dari Inventory #2 yang memakai `inInventory()` (termasuk `damaged`, `under_repair`, dll.). Sesuai plan: hanya **aktif**. |

---

### 2. Total nilai setelah depresiasi

| | |
|---|---|
| **Deskripsi** | Nilai buku keseluruhan item aktif yang memiliki skema depresiasi. |
| **Widget** | `StatsOverviewWidget` — 1 stat card + opsional persen penyusutan `(1 - nilai_buku/nilai_beli)`. |
| **Tabel** | `items`, `models`, `depreciations` |
| **Model** | `Item` |
| **Relasi** | `model.depreciation` |
| **Kolom status** | `status` — `ItemStatus::Active` |
| **Kolom** | `purchase_price`, `purchase_date`; accessor `depreciated_price` |
| **Query** | `Item::query()->where('status', ItemStatus::Active)->whereNotNull('purchase_price')->whereHas('model.depreciation')` — sum di PHP |
| **Agregat** | `SUM(depreciated_price)` computed |
| **Catatan** | Pasangkan dengan Finance #1 untuk tampilkan "Nilai beli → Nilai buku". |

---

### 3. Penyusutan bulan ini

| | |
|---|---|
| **Deskripsi** | Nilai penyusutan yang "terjadi" pada bulan berjalan — selisih nilai buku awal bulan vs akhir bulan. |
| **Widget** | `ChartWidget` — **bar** (per bulan, 6–12 bulan terakhir) atau **line** (tren). |
| **Tabel** | `items`, `models`, `depreciations` |
| **Model** | `Item` |
| **Relasi** | `model.depreciation` |
| **Kolom** | `purchase_price`, `purchase_date`; `depreciations.months`, `depreciations.minimum_value` |
| **Kolom status** | `ItemStatus::Active` + punya depresiasi |
| **Query** | Tidak ada kolom historis — **hitung di PHP** per item: `depreciated_price at startOfMonth` vs `depreciated_price at endOfMonth`, selisihnya = penyusutan bulan itu. Ulangi untuk setiap bulan dalam range chart. |
| **Agregat** | `SUM(penyusutan_per_item)` per bulan |
| **Catatan** | Widget paling kompleks — pertimbangkan job harian yang menyimpan snapshot `book_value` ke tabel cache jika performa jadi masalah. Tanpa cache, load semua item depresiasi ke memori. |

---

### 4. Item mendekati nilai minimum depresiasi

| | |
|---|---|
| **Deskripsi** | Item aktif yang nilai bukunya sudah mendekati nilai residu minimum menurut skema depresiasi. |
| **Widget** | `StatsOverviewWidget` (count) + `TableWidget` (top items). |
| **Tabel** | `items`, `models`, `depreciations` |
| **Model** | `Item` |
| **Relasi** | `model.depreciation` |
| **Kolom** | `purchase_price`; `depreciations.minimum_value` (%); accessor `depreciated_price` |
| **Kolom status** | `ItemStatus::Active` |
| **Query** | Load items dengan depresiasi, hitung `minimumValue = purchase_price * (minimum_value / 100)`. Filter item where `depreciated_price <= minimumValue * 1.1` (≤110% nilai minimum, atau threshold 10%). |
| **Agregat** | `COUNT(*)` |
| **Kolom tabel** | `serial_number`, `model.name`, `purchase_price`, `depreciated_price`, `minimumValue` |
| **Catatan** | Sesuai logika `DepreciationItemsPage` & accessor `Item::depreciatedPrice`. Item yang sudah fully depreciated masuk count ini. |

---

## Master & Administrasi

### 1. Total model / kategori

| | |
|---|---|
| **Deskripsi** | Jumlah master data model produk dan kategori inventori. |
| **Widget** | `StatsOverviewWidget` — 2 stat card dalam 1 widget. |
| **Tabel** | `models`, `categories` |
| **Model** | `Model`, `Category` |
| **Relasi** | `Category::models()` hasMany |
| **Kolom tipe** | `categories.type` — enum `CategoryType` |
| **Query** | `Model::query()->count()` dan `Category::query()->count()` |
| **Agregat** | `COUNT(*)` masing-masing tabel |
| **Catatan** | Opsional sub-breakdown kategori per `CategoryType` di description. |

---

### 2. Total supplier / manufacture

| | |
|---|---|
| **Deskripsi** | Jumlah vendor dan pabrikan terdaftar di master data. |
| **Widget** | `StatsOverviewWidget` — 2 stat card dalam 1 widget. |
| **Tabel** | `suppliers`, `manufactures` |
| **Model** | `Supplier`, `Manufacture` |
| **Relasi** | `Model::manufacture`, `Item::supplier` (untuk konteks, tidak dipakai di agregat) |
| **Query** | `Supplier::query()->count()` dan `Manufacture::query()->count()` |
| **Agregat** | `COUNT(*)` |
| **Catatan** | Widget referensi; prioritas rendah di dashboard operasional. |

---

### 3. Latest 5 activity log

| | |
|---|---|
| **Deskripsi** | Lima aktivitas sistem terbaru untuk audit trail cepat. |
| **Widget** | `TableWidget` — 5 baris, tanpa pagination. |
| **Tabel** | `activity_log` (Spatie) |
| **Model** | `ActivityLog` (extends `Spatie\Activitylog\Models\Activity`) |
| **Relasi** | `causer` → `User` (morph); `subject` → model terkait (morph) |
| **Kolom tampilan** | `causer.name`, `event`, `subject_type` (short class name), `description`, `created_at` |
| **Query** | `ActivityLog::query()->latest('created_at')->limit(5)` |
| **Agregat** | — (bukan agregat, daftar) |
| **Catatan** | Link ke `ActivityLogResource`. Format `subject_type` jadi nama resource yang terbaca. |

---

## Urutan layout dashboard (disarankan)

```
Baris 1 — StatsOverview (alert):
  Maintenance belum selesai | Audit jatuh tempo | Stok di bawah minimum | Item bermasalah | Item hilang

Baris 2 — StatsOverview (inventori):
  Total item | Total nilai aset | Nilai buku saat ini

Baris 3 — Charts (inventori):
  Item per status (doughnut) | Item per kategori (doughnut)

Baris 4 — Charts (inventori):
  Item per lokasi (bar) | Item per departemen (bar)

Baris 5 — Stats + Tables (deadline):
  Aset mendekati EOL (stat) | Garansi hampir habis (stat)

Baris 6 — Audit & maintenance:
  Audit bulan ini | Hasil audit bermasalah | Lokasi tidak terverifikasi | Biaya maintenance bulan ini

Baris 7 — Charts + stat:
  Maintenance per tipe (chart) | Item tanpa audit (stat)

Baris 8 — Stock:
  Pergerakan stok hari ini | Consumable kritis (stat) | Top consumable (bar chart)

Baris 9 — Finance:
  Total nilai pembelian | Total nilai setelah depresiasi | Item mendekati nilai minimum

Baris 10 — Finance chart + master:
  Penyusutan bulan ini (line/bar) | Model/kategori count | Supplier/manufacture count

Baris 11 — Activity:
  Latest 5 activity log (table)
```

---

## Catatan implementasi umum

1. **Accessor `depreciated_price`** — widget finance (#Inventory 3, #Finance 2–4) wajib iterasi collection atau cache; tidak ada kolom DB.
2. **Filter `inInventory()` vs `Active`** — gunakan konsisten sesuai spesifikasi per widget (sudah ditulis di masing-masing item).
3. **Shield / policy** — setiap widget perlu `canView()` sesuai permission resource terkait.
4. **Drill-down** — stat card sebaiknya punya `url()` ke resource dengan query filter yang sesuai.
5. **Polling** — widget alert (baris 1) bisa `->poll('60s')` jika ingin near-real-time tanpa refresh manual.

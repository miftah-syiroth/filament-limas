# Dashboard

Dashboard SIRIS (`app/Filament/Pages/Dashboard.php`) menampilkan ringkasan operasional inventori melalui widget-widget yang tersusun dalam beberapa baris.

## Konvensi Scope

- `Item::inInventory()` — mengecualikan status `lost`, `stolen`, `archived`, `disposed`
- Item **aktif** — `status = active`
- Nilai moneter diformat IDR tanpa desimal

## Baris Alert / KPI

Widget peringatan di bagian atas dashboard:

| Widget | Keterangan | Warna |
|--------|------------|-------|
| Maintenance belum selesai | Tiket status `reported` atau `in_progress` | Warning |
| Audit jatuh tempo | Item dengan `next_audit_date` lewat atau ≤ 7 hari | Danger/Warning |
| Stok kritis | Model dengan stok di bawah `min_amount` | Warning |
| Item bermasalah | Status `damaged`, `under_repair`, dll. | Danger |
| Item hilang/dicuri | Status `lost` atau `stolen` | Danger |

## Inventori

| Widget | Tipe | Keterangan |
|--------|------|------------|
| Inventory stats | StatsOverview | Total item, nilai pembelian |
| Status doughnut | ChartWidget | Distribusi status item |
| Category bar | ChartWidget | Item per kategori |
| Location bar | ChartWidget | Item per lokasi |
| Department bar | ChartWidget | Item per departemen |
| EOL table | TableWidget | Item mendekati end-of-life |
| Expiring warranty | TableWidget | Garansi akan berakhir |

## Audit & Maintenance

| Widget | Keterangan |
|--------|------------|
| AuditMaintenanceStatsOverview | Ringkasan audit dan maintenance |
| MaintenanceByTypeChart | Grafik maintenance per tipe |

## Stok

| Widget | Keterangan |
|--------|------------|
| Today stock movements | Pergerakan stok hari ini |
| Critical consumables | Consumable di bawah minimum |
| Top consumable chart | Consumable paling aktif |

## Keuangan

| Widget | Keterangan |
|--------|------------|
| Purchase/book value stats | Nilai pembelian vs nilai buku |
| Monthly depreciation chart | Tren depresiasi bulanan |
| Near minimum depreciation | Item mendekati nilai residu minimum |

## Master Data

Widget ringkasan jumlah: models, categories, suppliers, manufactures.

## Aktivitas

| Widget | Keterangan |
|--------|------------|
| LatestActivityLogTable | 10 aktivitas terbaru dari Spatie Activity Log |

## Barcode Scanner

Widget `BarcodeScanner` memungkinkan scan nomor seri 8 karakter untuk langsung membuka halaman view item terkait.

## Halaman Depresiasi

Selain widget, laporan depresiasi lengkap tersedia di halaman **Depreciation Items** (`DepreciationItemsPage`) dengan opsi ekspor.

## Langkah Selanjutnya

- [Modul Inventori](/{{route}}/{{version}}/modul/inventori)
- [Referensi Enum](/{{route}}/{{version}}/referensi/enum)

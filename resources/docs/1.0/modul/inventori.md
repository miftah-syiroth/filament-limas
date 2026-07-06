# Inventori (Items)

Modul inti SIRIS untuk melacak setiap unit aset individual.

**Resource:** `ItemResource` · **Model:** `App\Models\Item`

## Halaman

| Halaman | Keterangan |
|---------|------------|
| List | Tabel semua item dengan filter dan pencarian |
| Create | Tambah item baru |
| View | Detail item + barcode |
| Edit | Ubah data item |

## Data Utama

| Field | Keterangan |
|-------|------------|
| `serial_number` | Nomor seri unik (8 karakter, untuk barcode) |
| `model_id` | Template produk |
| `location_id` | Lokasi fisik saat ini |
| `department_id` | Departemen penanggung jawab |
| `room_id` | Ruangan |
| `user_id` | Penanggung jawab |
| `supplier_id` | Pemasok pengadaan |
| `quantity` | Jumlah (default 1) |
| `purchase_date` / `purchase_price` | Data pembelian |
| `eol_date` | Tanggal end-of-life |
| `warranty_months` | Masa garansi |
| `is_individual_tracking` | Pelacakan per unit vs stok |
| `status` | Status operasional (lihat [Enum ItemStatus](/{{route}}/{{version}}/referensi/enum#itemstatus)) |

## Status Item

Status menentukan apakah item masuk scope inventori (`inInventory()`). Status `lost`, `stolen`, `archived`, dan `disposed` dikecualikan dari perhitungan inventori.

## Media & Barcode

- **Foto aset** — Spatie Media Library, disk `public`
- **Barcode** — komponen `QrCodeEntry` menampilkan Code 128 dari nomor seri

## Sub-halaman Item

Dari record item, akses tab terkait:

| Tab | Keterangan |
|-----|------------|
| [Stock Movements](/{{route}}/{{version}}/modul/pergerakan-stok) | Pergerakan stok consumable |
| [Borrowing History](/{{route}}/{{version}}/modul/peminjaman) | Riwayat peminjaman |
| [State Logs](/{{route}}/{{version}}/modul/riwayat-status) | Transfer permanen, assignment |
| [Audits](/{{route}}/{{version}}/modul/audit) | Inspeksi fisik |
| [Maintenances](/{{route}}/{{version}}/modul/maintenance) | Perawatan/perbaikan |

## Scope & Accessor

| Method | Keterangan |
|--------|------------|
| `inInventory()` | Scope query — exclude lost/stolen/archived/disposed |
| `borrowable()` | Item yang dapat dipinjamkan |
| `depreciated_price` | Nilai buku setelah depresiasi |
| `borrowable_quantity` | Qty tersedia untuk dipinjam |

## Global Search

Item dapat dicari dari pencarian global panel berdasarkan serial, model, lokasi, departemen, ruang, dan penanggung jawab.

## Impor & Ekspor

- **Import:** `ItemImporter` — impor massal dari spreadsheet
- **Export:** `ItemExporter` — ekspor data item

Lihat [Impor & Ekspor](/{{route}}/{{version}}/modul/impor-ekspor).

## Widget Terkait

- Barcode Scanner di dashboard
- Grafik status, kategori, lokasi
- Tabel EOL dan garansi

## Langkah Selanjutnya

- [Peminjaman](/{{route}}/{{version}}/modul/peminjaman)
- [Referensi Database](/{{route}}/{{version}}/referensi/database#items)

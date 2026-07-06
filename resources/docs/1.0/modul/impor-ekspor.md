# Impor & Ekspor

SIRIS mendukung impor massal dan ekspor data melalui fitur Filament Import/Export (Maatwebsite Excel).

## Impor

| Importer | Resource | Data |
|----------|----------|------|
| `CategoryImporter` | Categories | Kategori aset |
| `ManufactureImporter` | Manufactures | Produsen |
| `ModelImporter` | Models | Template produk |
| `ItemImporter` | Items | Unit inventori |

### Cara Impor

1. Buka resource terkait → tombol **Import**
2. Upload file spreadsheet (format sesuai template importer)
3. Review dan konfirmasi
4. Sistem memvalidasi dan menyimpan record

> {primary} Urutan impor
>
> Impor data master terlebih dahulu (categories → manufactures → models) sebelum mengimpor items, karena item memerlukan referensi model dan lokasi.

## Ekspor

| Exporter | Resource / Halaman | Data |
|----------|-------------------|------|
| `ItemExporter` | Items | Seluruh data item |
| `ItemAuditExporter` | Item Audits | Data audit |
| `MaintenanceExporter` | Maintenances | Tiket maintenance |
| `BorrowingItemExporter` | Borrowing Items | Line item peminjaman |
| `DepreciationItemExporter` | Depreciation Items Page | Laporan nilai buku |

### Cara Ekspor

1. Buka resource atau halaman laporan
2. Klik tombol **Export**
3. Pilih kolom (jika tersedia)
4. Download file hasil

## Format

Ekspor/impor menggunakan format spreadsheet yang kompatibel dengan **Maatwebsite Excel** (`.xlsx`). Kolom mengikuti struktur form resource terkait.

## Aktivitas

Impor dan ekspor tercatat di tabel `imports` / `exports` (sistem Filament) dan dapat dilacak melalui activity log.

## Langkah Selanjutnya

- [Inventori](/{{route}}/{{version}}/modul/inventori)
- [Pengguna](/{{route}}/{{version}}/administrasi/pengguna)

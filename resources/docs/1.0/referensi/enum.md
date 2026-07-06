# Referensi Enum

Daftar enum domain yang digunakan di SIRIS. Label tampilan diambil dari file terjemahan `lang/id/` dan `lang/en/`.

## ItemStatus

Status operasional item inventori.

| Nilai | Keterangan |
|-------|------------|
| `active` | Aktif, siap pakai |
| `under_diagnosis` | Sedang didiagnosis |
| `under_repair` | Sedang diperbaiki |
| `damaged` | Rusak |
| `irreparable` | Tidak dapat diperbaiki |
| `lost` | Hilang |
| `stolen` | Dicuri |
| `archived` | Diarsipkan |
| `disposed` | Dibuang/disposal |

**Excluded from inventory:** `lost`, `stolen`, `archived`, `disposed`

## CategoryType

| Nilai | Keterangan |
|-------|------------|
| `asset` | Aset tetap |
| `accessory` | Aksesori |
| `consumable` | Barang habis pakai |
| `license` | Lisensi software |

## BorrowingStatus

| Nilai | Keterangan |
|-------|------------|
| `active` | Sedang dipinjam |
| `returned` | Semua item sudah kembali |

## MaintenanceType

| Nilai | Keterangan |
|-------|------------|
| `preventive` | Perawatan preventif |
| `repair` | Perbaikan |
| `upgrade` | Upgrade |
| `inspection` | Inspeksi |

## MaintenanceStatus

| Nilai | Keterangan |
|-------|------------|
| `reported` | Dilaporkan |
| `in_progress` | Sedang dikerjakan |
| `completed` | Selesai |
| `cancelled` | Dibatalkan |

## ItemAuditCondition

| Nilai | Keterangan |
|-------|------------|
| `excellent` | Sangat baik |
| `good` | Baik |
| `fair` | Cukup |
| `poor` | Buruk |
| `unusable` | Tidak layak pakai |

## ItemAuditResult

| Nilai | Keterangan |
|-------|------------|
| `ok` | Baik, tidak perlu tindakan |
| `needs_maintenance` | Perlu perawatan |
| `needs_replacement` | Perlu penggantian |
| `dispose` | Perlu disposal |

## StockMovementType

| Nilai | Keterangan |
|-------|------------|
| `in` | Stok masuk |
| `out` | Stok keluar |
| `adjustment` | Penyesuaian |

## ItemStateEventType

| Nilai | Keterangan |
|-------|------------|
| `transfer` | Transfer lokasi permanen |
| `assignment` | Perubahan penanggung jawab |
| `status_change` | Perubahan status |

## DepreciationMethod

| Nilai | Keterangan |
|-------|------------|
| `amount` | Garis lurus (straight line) |

## RoleName

| Nilai | Label |
|-------|-------|
| `super_admin` | Super Admin |
| `admin` | Admin |
| `operator` | Operator |

## Lokasi Kode

Enum berada di `app/Enums/`. Untuk detail kolom database, lihat [Referensi Database](/{{route}}/{{version}}/referensi/database).

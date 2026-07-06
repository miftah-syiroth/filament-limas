# Pergerakan Stok

Modul untuk mencatat pergerakan stok **consumable** (barang habis pakai) yang tidak dilacak per unit.

**Sub-halaman:** Item → tab Stock Movements · **Model:** `App\Models\StockMovement`

## Kapan Digunakan

Gunakan stock movement untuk item dengan `is_individual_tracking = false` atau kategori tipe `consumable`. Untuk aset individual, gunakan [Peminjaman](/{{route}}/{{version}}/modul/peminjaman) atau [Riwayat Status](/{{route}}/{{version}}/modul/riwayat-status).

## Tipe Pergerakan

| Tipe | Keterangan | Dampak Qty |
|------|------------|------------|
| `in` | Stok masuk | Bertambah |
| `out` | Stok keluar | Berkurang |
| `adjustment` | Koreksi stok | Sesuai nilai |

Lihat [StockMovementType](/{{route}}/{{version}}/referensi/enum#stockmovementtype).

## Data

| Field | Keterangan |
|-------|------------|
| `item_id` | Item consumable |
| `type` | in / out / adjustment |
| `quantity` | Jumlah (positif/negatif sesuai tipe) |
| `notes` | Keterangan pergerakan |

## Alert Stok Minimum

Model produk (`models.min_amount`) mendefinisikan stok minimum. Dashboard menampilkan widget **Critical consumables** untuk item di bawah ambang batas.

## Dashboard

| Widget | Keterangan |
|--------|------------|
| Today stock movements | Pergerakan hari ini |
| Critical consumables | Stok di bawah minimum |
| Top consumable chart | Consumable paling aktif |

> {note} Bukan untuk pemindahan fisik
>
> Stock movement mengubah **kuantitas** stok secara permanen, bukan posisi fisik aset. Untuk pemindahan lokasi sementara, gunakan modul Peminjaman.

## Langkah Selanjutnya

- [Riwayat Status](/{{route}}/{{version}}/modul/riwayat-status)
- [Referensi Database](/{{route}}/{{version}}/referensi/database#stock_movements)

# Pergerakan Stok

**Pergerakan stok** digunakan untuk barang **habis pakai (consumable)** — mencatat stok masuk dan keluar secara permanen.

## Siapa yang Bisa Mengakses

Operator dan admin dengan izin pergerakan stok pada barang.

## Cara Membuka

1. Buka detail barang kategori consumable
2. Klik tab **Stok**

> {note} Bukan untuk pemindahan fisik
>
> Pergerakan stok mengubah **jumlah** stok, bukan memindahkan aset ke lokasi lain. Untuk pemindahan sementara gunakan [Peminjaman](/{{route}}/{{version}}/modul/peminjaman). Untuk pindah permanen gunakan [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status).

## Kapan Menggunakan?

- Stok ATK masuk dari pengadaan baru
- Stok keluar karena pemakaian harian
- Koreksi jumlah stok setelah opname

## Mencatat Pergerakan Stok

1. Buka barang consumable → tab **Stok**
2. Klik **Tambah stok**
3. Pilih **tipe**:
   - **Masuk (In)** — tambah stok (kuantitas positif)
   - **Keluar (Out)** — kurangi stok (kuantitas negatif)
4. Isi **kuantitas** dan **catatan**
5. Simpan

Sistem mencegah stok menjadi negatif.

## Peringatan Stok Minimum

Jika stok consumable di bawah batas minimum yang ditetapkan, peringatan muncul di **dasbor**.

## Langkah Selanjutnya

- [Barang](/{{route}}/{{version}}/modul/inventori)
- [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status)

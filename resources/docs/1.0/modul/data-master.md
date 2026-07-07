# Data Master

**Data master** adalah data referensi organisasi dan pengaturan pendukung yang harus ada sebelum mencatat barang.

## Siapa yang Bisa Mengakses

Umumnya **admin**. Operator mungkin hanya melihat, tergantung izin.

## Cara Membuka

Sidebar → grup **Data Master**

## Organisasi

Mencatat institusi pemilik aset.

1. Klik **Organisasi** → **Tambah**
2. Isi nama, kontak (email/telepon), dan catatan
3. Simpan

## Lokasi

Cabang atau kantor di bawah organisasi.

1. Klik **Lokasi** → **Tambah**
2. Pilih **organisasi** induk
3. Isi nama dan alamat lengkap
4. Simpan

## Departemen

Unit kerja yang dapat terhubung ke satu atau lebih lokasi.

1. Klik **Departemen** → **Tambah**
2. Isi nama departemen
3. Pilih lokasi yang terkait
4. Simpan

## Ruangan

Ruang fisik dalam suatu lokasi.

1. Klik **Ruangan** → **Tambah**
2. Pilih **lokasi** induk
3. Isi nama ruangan dan kapasitas (opsional)
4. Simpan

## Penyusutan

Aturan penyusutan nilai aset untuk laporan keuangan.

1. Klik **Penyusutan** → **Tambah**
2. Isi nama aturan, metode, persentase, dan nilai residu
3. Simpan
4. Hubungkan aturan penyusutan ke **model** barang di grup Referensi

## Satuan

Satuan ukuran barang (pcs, unit, rim, dll.).

1. Klik **Satuan**
2. Tambah atau kelola satuan dari halaman tersebut

## Data Referensi (Katalog Produk)

Selain data master, lengkapi katalog di grup **Referensi** sebelum menambah barang:

| Menu | Fungsi |
|------|--------|
| **Kategori** | Jenis barang (elektronik, furniture, consumable, dll.) |
| **Pabrikan** | Produsen |
| **Model** | Template produk — hubungkan ke kategori, pabrikan, dan penyusutan |
| **Pemasok** | Vendor pengadaan |

> {primary} Urutan pengisian disarankan
>
> Isi **Organisasi → Lokasi → Departemen → Ruangan**, lalu **Kategori → Pabrikan → Model**, baru tambahkan **Barang**.

## Langkah Selanjutnya

- [Barang](/{{route}}/{{version}}/modul/item)
- [Impor & Ekspor](/{{route}}/{{version}}/modul/impor-ekspor)

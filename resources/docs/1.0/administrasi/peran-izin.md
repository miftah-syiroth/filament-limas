# Peran & Izin

SIRIS menggunakan **peran** untuk menentukan apa yang dapat dilihat dan dilakukan setiap pengguna di panel admin.

## Siapa yang Bisa Mengakses

**Super admin** dan **admin** dengan izin kelola peran.

## Cara Membuka

Sidebar → grup **Administrasi** → **Peran**

## Peran Bawaan

| Peran | Keterangan umum |
|-------|-----------------|
| **Super Admin** | Akses penuh ke seluruh fitur tanpa batasan |
| **Admin** | Mengelola inventori, data master, laporan, dan pengguna |
| **Operator** | Fokus operasional harian — peminjaman, audit, stok |

> {note} Operator
>
> Izin operator dapat disesuaikan oleh admin institusi melalui halaman **Peran** jika kebutuhan berbeda.

## Apa yang Dikontrol Peran?

Setiap peran memiliki izin per menu dan per aksi, misalnya:

- Melihat daftar barang
- Menambah atau mengubah barang
- Mengekspor laporan
- Mengelola pengguna

Menu yang tidak memiliki izin **tidak akan muncul** di sidebar pengguna tersebut.

## Mengelola Peran Kustom

1. Buka **Peran** → **Tambah** (untuk peran baru) atau pilih peran yang ada
2. Centang izin yang sesuai untuk setiap menu
3. Simpan

> {warning} Peran bawaan
>
> Peran **super admin**, **admin**, dan **operator** tidak dapat diubah strukturnya. Untuk kebutuhan khusus, buat **peran kustom** baru.

## Perbandingan Singkat

| Kemampuan | Super Admin | Admin | Operator |
|-----------|:-----------:|:-----:|:--------:|
| Kelola barang & peminjaman | Ya | Ya | Sesuai izin |
| Lihat laporan | Ya | Ya | Sesuai izin |
| Kelola data master | Ya | Ya | Terbatas |
| Kelola pengguna & peran | Ya | Ya | Tidak |

## Menetapkan Peran ke Pengguna

Peran diberikan saat membuat atau mengubah pengguna di menu **Pengguna**. Satu pengguna dapat memiliki lebih dari satu peran.

## Langkah Selanjutnya

- [Pengguna](/{{route}}/{{version}}/administrasi/pengguna)
- [Navigasi panel](/{{route}}/{{version}}/panel-admin/navigasi)

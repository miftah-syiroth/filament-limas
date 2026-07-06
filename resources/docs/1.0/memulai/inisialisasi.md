# Inisialisasi

Panduan ini menjelaskan alur dan data yang perlu disiapkan sebelum SIRIS siap dipakai untuk mencatat barang. Ikuti urutan di bawah: pastikan akses login, lengkapi data master, lalu data referensi.

---

- [Akses Login](#akses-login)
- [Data Master](#data-master)
- [Data Referensi](#data-referensi)

<a name="akses-login"></a>
## Akses Login

Sebelum mengisi data, pastikan Anda dapat masuk ke panel admin SIRIS.

Anda memerlukan **email** dan **password** akun yang sudah terdaftar. Jika ada kendala atau pertanyaan terkait akses login, hubungi **tim IT**. Lihat informasi login pada halaman [Login](/{{route}}/{{version}}/autentikasi/login).

> {info} Syarat login
>
> Akun harus sudah terdaftar dan memiliki **minimal satu peran**. Tanpa peran, login akan ditolak meskipun email dan password benar.

<a name="data-master"></a>
## Data Master

Lengkapi data di grup sidebar **Data Master**. Beberapa menu wajib diisi, beberapa dianjurkan agar pencatatan barang berjalan lancar.

| Menu | Keterangan | Status pengisian |
|------|------------|------------------|
| **Organisasi** | Organisasi atau perusahaan | **Wajib** |
| **Lokasi** | Cabang atau kantor tempat aset berada | **Wajib** |
| **Departemen** | Unit kerja penanggung jawab aset | **Dianjurkan** |
| **Ruangan** | Ruang fisik di dalam lokasi | **Dianjurkan** |
| **Satuan** | Satuan ukuran barang (pcs, unit, rim, dll.) | Opsional |
| **Penyusutan** | Aturan perhitungan nilai buku aset | Opsional — **isi jika perlu menghitung depresiasi** |

Saat menambah **Lokasi**, pastikan organisasi induk sudah ada. Untuk langkah pengisian tiap menu, buka grup **Data Master** di sidebar atau lihat [Lokasi](/{{route}}/{{version}}/master/lokasi).

<a name="data-referensi"></a>
## Data Referensi

Setelah data master siap, lengkapi katalog di grup sidebar **Referensi** sebelum menambah barang.

| Menu | Maksud data | Status pengisian |
|------|-------------|------------------|
| **Pemasok** | Nama toko, vendor, atau individu sumber pengadaan barang | Opsional |
| **Pabrikan** | Merk, brand, atau produsen barang | **Wajib** |
| **Kategori** | Jenis atau golongan barang (Aset, aksesoris, consumable, komputer, meja, kursi, dll.) | **Wajib** |
| **Model** | Informasi yang tertaut pada suatu barang, seperti spesifikasi teknis, lokasi, serial number, warna | **Wajib** |

## Langkah Selanjutnya

- [Login](/{{route}}/{{version}}/autentikasi/login) — masuk ke sistem
- [Kategori](/{{route}}/{{version}}/modul/data-master) — konsep tipe barang (Aset, Aksesoris, dan Habis Pakai)
- [Model](/{{route}}/{{version}}/modul/inventori) — mencatat data spesifikasi model barang.
- [Barang](/{{route}}/{{version}}/modul/inventori) — mencatat kondisi individu barang, lokasi barang, dan serial number. 

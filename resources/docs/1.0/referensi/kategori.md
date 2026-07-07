# Kategori

Menu **Kategori** mencatat golongan atau jenis barang di SIRIS. Setiap kategori memiliki **Tipe** — **Aset**, **Aksesori**, **Habis pakai**, atau **Lisensi** — yang memengaruhi cara **Barang** dicatat (per unit atau stok bulk). Isi kategori sebelum membuat **Model**. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi#data-referensi) dan [Ringkasan](/{{route}}/{{version}}/overview).

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)
- [Impor Data](#impor-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Model** | Satu kategori dapat memiliki banyak model |
| **Barang** | Tipe kategori menentukan perilaku pencatatan barang (unit vs stok) |

**Tipe kategori:**

| Tipe | Arti |
|------|------|
| **Aset** | Barang inventori utama, biasanya per unit |
| **Aksesori** | Perlengkapan pendukung aset |
| **Habis pakai** | Barang stok bulk (consumable) |
| **Lisensi** | Lisensi perangkat lunak atau layanan |

Urutan pengisian: **Kategori** → **Pabrikan** → **Model**.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Kategori**
2. Isi field berikut:
   - **Nama** — wajib
   - **Tipe** — wajib, pilih **Aset**, **Aksesori**, **Habis pakai**, atau **Lisensi**
   - **Catatan** — opsional
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar kategori, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan
3. Klik **Simpan**

> {info} Perubahan tipe
>
> Mengubah **Tipe** kategori yang sudah memiliki **Model** dapat memengaruhi cara barang terkait dicatat. Pastikan perubahan sesuai kebutuhan operasional.

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka kategori → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Kategori yang masih dipakai oleh **Model** sebaiknya tidak dihapus. Periksa kolom **Jumlah Model** di tabel sebelum menghapus.

<a name="impor-data"></a>
## Impor Data

Menu **Kategori** mendukung impor dari file CSV. Ekspor tidak tersedia di menu ini.

1. Klik tombol **Impor** di atas tabel
2. Unggah file CSV
3. Petakan kolom file ke field berikut:
   - **name** — wajib
   - **type** — wajib (`asset`, `accessory`, `consumable`, atau `license`)
   - **notes** — opsional
4. Jalankan impor dan tunggu notifikasi hasil

Baris dengan **name** yang sama akan diperbarui jika sudah ada di sistem.

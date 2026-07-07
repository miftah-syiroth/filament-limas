# Pabrikan

Menu **Pabrikan** mencatat merk, brand, atau produsen barang. Data ini wajib diisi sebelum membuat **Model** dan membantu melacak asal produk serta informasi dukungan garansi. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi#data-referensi) dan [Ringkasan](/{{route}}/{{version}}/overview).

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
| **Model** | Satu pabrikan dapat memiliki banyak model |

**Prasyarat:** tidak ada — **Pabrikan** dapat diisi setelah **Kategori** dan sebelum **Model**.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Pabrikan**
2. Isi field berikut:
   - **Nama** — wajib
   - **URL situs web** — opsional
   - **URL dukungan** — opsional
   - **Telepon dukungan** — opsional
   - **Email dukungan** — opsional
   - **URL cek garansi** — opsional
   - **Catatan** — opsional
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar pabrikan, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan
3. Klik **Simpan**

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka pabrikan → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Pabrikan yang masih dipakai oleh **Model** sebaiknya tidak dihapus. Periksa kolom **Jumlah Model** di tabel sebelum menghapus.

<a name="impor-data"></a>
## Impor Data

Menu **Pabrikan** mendukung impor dari file CSV. Ekspor tidak tersedia di menu ini.

1. Klik tombol **Impor** di atas tabel
2. Unggah file CSV
3. Petakan kolom file ke field berikut:
   - **name** — wajib
   - **url** — opsional
   - **support_url** — opsional
   - **support_phone** — opsional
   - **support_email** — opsional
   - **warranty_lookup_url** — opsional
   - **notes** — opsional
4. Jalankan impor dan tunggu notifikasi hasil

Baris dengan **name** yang sama akan diperbarui jika sudah ada di sistem.

# Model

Menu **Model** mencatat template produk — spesifikasi induk sebelum Anda menambah **Barang** individual (nomor seri, lokasi, kondisi fisik). Setiap model terhubung ke **Kategori**, **Pabrikan**, dan **Satuan**; opsional ke **Penyusutan** untuk perhitungan depresiasi. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi#data-referensi) dan [Ringkasan](/{{route}}/{{version}}/overview).

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
| **Kategori** | Wajib — menentukan tipe barang |
| **Pabrikan** | Wajib |
| **Satuan** | Wajib (dari grup **Data Master**) |
| **Penyusutan** | Opsional — mengisi **Masa pakai** otomatis dari aturan depresiasi |
| **Barang** | Satu model dapat memiliki banyak barang |

**Prasyarat:** **Kategori**, **Pabrikan**, dan **Satuan** sudah terisi. Isi **Penyusutan** di Data Master jika perlu menghitung depresiasi.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Model**
2. Isi field berikut:
   - **Pabrikan** — wajib (dapat dibuat cepat dari form jika belum ada)
   - **Kategori** — wajib (dapat dibuat cepat dari form jika belum ada)
   - **Satuan** — wajib (dapat dibuat cepat dari form jika belum ada)
   - **Nama** — wajib
   - **Nomor model** — opsional
   - **Depresiasi** — opsional; jika dipilih, **Masa pakai** terisi otomatis
   - **Stok minimal** — wajib, default 1; batas peringatan stok minimum
   - **Masa pakai** — opsional, dalam bulan; terkunci jika **Depresiasi** dipilih
   - **Interval audit** — opsional, dalam bulan
   - **Catatan** — opsional
   - **Gambar** — opsional, maksimal 3 file
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar model, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan, termasuk gambar
3. Klik **Simpan**

> {info} Dampak perubahan
>
> Mengubah **Kategori** atau **Depresiasi** pada model yang sudah memiliki **Barang** dapat memengaruhi perilaku pencatatan dan perhitungan nilai buku.

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka model → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Model yang masih memiliki **Barang** sebaiknya tidak dihapus. Periksa kolom jumlah barang di tabel sebelum menghapus.

<a name="impor-data"></a>
## Impor Data

Menu **Model** mendukung impor dari file CSV. Ekspor tidak tersedia di menu ini.

1. Klik tombol **Impor** di atas tabel
2. Unggah file CSV
3. Petakan kolom file ke field berikut:
   - **name** — wajib
   - **model_number** — opsional
   - **min_amount** — opsional
   - **end_of_life** — opsional, dalam bulan
   - **manufacture** — nama **Pabrikan** yang sudah ada
   - **category** — nama **Kategori** yang sudah ada
   - **depreciation** — nama aturan **Penyusutan** yang sudah ada
   - **unit** — nama **Satuan** yang sudah ada
   - **audit_interval** — opsional, dalam bulan
   - **notes** — opsional
4. Jalankan impor dan tunggu notifikasi hasil

Pastikan **Pabrikan**, **Kategori**, **Satuan**, dan **Penyusutan** (jika dipakai) sudah ada di sistem sebelum mengimpor model. Baris dengan **name** yang sama akan diperbarui jika sudah ada.

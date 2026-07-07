# Pemasok

Menu **Pemasok** mencatat toko, vendor, atau individu sebagai sumber pengadaan barang. Data ini opsional, tetapi berguna saat mencatat **Barang** — pemasok dipilih di level barang, bukan di **Model**. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi#data-referensi) dan [Ringkasan](/{{route}}/{{version}}/overview).

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Barang** | Pemasok dapat ditautkan ke barang saat pencatatan pengadaan |

**Catatan:** **Pemasok** tidak terhubung langsung ke **Model**. Isi kapan saja sebelum atau bersamaan pencatatan **Barang**.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Pemasok**
2. Isi field berikut:
   - **Nama** — wajib
   - **Negara** — default Indonesia (tidak perlu diubah)
   - **Provinsi** — opsional
   - **Kota** — opsional, pilih setelah provinsi
   - **Alamat** — opsional
   - **Alamat baris 2** — opsional
   - **Kode pos** — opsional
   - **Telepon** — opsional
   - **Email** — opsional
   - **URL situs web** — opsional
   - **Catatan** — opsional
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar pemasok, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan
3. Klik **Simpan**

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka pemasok → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Pemasok yang masih dipakai oleh **Barang** sebaiknya tidak dihapus. Periksa kolom **Jumlah Barang** di tabel sebelum menghapus.

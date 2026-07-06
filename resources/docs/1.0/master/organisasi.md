# Organisasi

Menu **Organisasi** mencatat entitas institusi pemilik aset di SIRIS — misalnya universitas, yayasan, atau unit induk lainnya. Data ini menjadi dasar hierarki data master; setiap **Lokasi** dan **Departemen** harus berada di bawah organisasi. Lihat konteks pengisian awal di [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi) dan [Ringkasan](/{{route}}/{{version}}/overview).

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Lokasi** | Satu organisasi memiliki banyak lokasi |
| **Departemen** | Satu organisasi memiliki banyak departemen |

Urutan pengisian: isi **Organisasi** terlebih dahulu, baru **Lokasi** dan **Departemen**.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Organisasi**
2. Isi field berikut:
   - **Nama** — wajib
   - **Alamat email** — opsional
   - **Telepon** — opsional
   - **Catatan** — opsional
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar organisasi, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan
3. Klik **Simpan**

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka organisasi → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {info} Soft delete
>
> Data yang dihapus disembunyikan dari daftar utama, tetapi dapat dipulihkan oleh admin jika fitur pemulihan tersedia di lingkungan Anda.

> {warning} Data terkait
>
> Menghapus organisasi dapat memengaruhi **Lokasi** dan **Departemen** di bawahnya. Pastikan tidak ada ketergantungan penting sebelum menghapus.

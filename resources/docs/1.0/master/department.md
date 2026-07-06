# Departemen

Menu **Departemen** mencatat unit kerja atau divisi yang menjadi penanggung jawab operasional aset — misalnya fakultas, bagian administrasi, atau laboratorium. Departemen terhubung ke **Organisasi** dan satu atau lebih **Lokasi**; saat mencatat barang, departemen membantu melacak siapa yang mengelola aset tersebut. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi) dan [Ringkasan](/{{route}}/{{version}}/overview).

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Organisasi** | Setiap departemen milik satu organisasi (terisi otomatis) |
| **Lokasi** | Departemen dapat terhubung ke satu atau lebih lokasi |
| **Barang** | Barang dapat dicatat pada departemen tertentu |
| **Peminjaman** | Peminjaman dapat menargetkan departemen tujuan |

**Prasyarat:** **Organisasi** dan minimal satu **Lokasi** sudah terisi.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Departemen**
2. Isi field berikut:
   - **Organisasi** — terisi otomatis (tidak perlu diubah)
   - **Lokasi** — wajib, pilih satu atau lebih lokasi yang terkait
   - **Nama** — wajib
   - **Telepon** — opsional
   - **Catatan** — opsional
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar departemen, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan, termasuk lokasi yang terhubung
3. Klik **Simpan**

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka departemen → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Departemen yang masih memiliki **Barang** terkait sebaiknya tidak dihapus. Periksa kolom jumlah item di tabel sebelum menghapus.

# Ruangan

Menu **Ruangan** mencatat unit ruang fisik di bawah suatu **Lokasi** — misalnya kelas, laboratorium, atau gudang. Dengan mencatat ruangan, Anda dapat mengetahui aset berada di ruang mana, tidak hanya di level kampus atau gedung. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi) untuk urutan pengisian data master.

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Lokasi** | Setiap ruangan milik satu lokasi (wajib) |
| **Barang** | Barang dapat ditempatkan pada ruangan tertentu |
| **Peminjaman** | Peminjaman dapat menargetkan ruangan tujuan |

**Prasyarat:** **Lokasi** sudah terisi sebelum menambah ruangan.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Ruangan**
2. Isi field berikut:
   - **Lokasi** — wajib, pilih lokasi induk
   - **Nama** — wajib
   - **Kapasitas** — wajib, angka minimal 1
   - **Catatan** — opsional
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar ruangan, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan
3. Klik **Simpan**

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka ruangan → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Ruangan yang masih memiliki **Barang** sebaiknya tidak dihapus. Periksa kolom **Barang** di tabel sebelum menghapus.

# Lokasi

Menu **Lokasi** mencatat cabang atau kantor fisik tempat aset berada — misalnya kampus utama, kampus cabang, atau kantor regional. Setiap lokasi berada di bawah **Organisasi** dan menjadi acuan **Ruangan**, **Departemen**, serta pencatatan **Barang** agar aset dapat dilacak. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi) untuk urutan pengisian data master.

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Organisasi** | Setiap lokasi milik satu organisasi (wajib) |
| **Ruangan** | Satu lokasi dapat memiliki banyak ruangan |
| **Departemen** | Departemen dapat terhubung ke satu atau lebih lokasi |
| **Barang** | Barang dicatat pada lokasi tertentu |
| **Peminjaman** | Peminjaman dapat menargetkan lokasi tujuan |

**Prasyarat:** **Organisasi** sudah terisi sebelum menambah lokasi.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah Lokasi**
2. Isi field berikut:
   - **Organisasi** — wajib, pilih organisasi induk
   - **Nama** — wajib
   - **Negara** — default Indonesia (tidak perlu diubah)
   - **Provinsi** — pilih provinsi
   - **Kota** — pilih kota setelah provinsi dipilih
   - **Alamat** — wajib
   - **Alamat baris 2** — opsional
   - **Kode pos** — opsional
   - **Telepon** — opsional
   - **Catatan** — opsional
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar lokasi, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan
3. Klik **Simpan**

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka lokasi → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Lokasi yang masih memiliki **Ruangan**, **Barang**, atau keterkaitan **Departemen** sebaiknya tidak dihapus. Periksa jumlah barang di kolom tabel sebelum menghapus.

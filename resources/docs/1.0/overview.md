# Ringkasan

**SIRIS** (*Sistem Informasi Inventori*) adalah aplikasi manajemen aset dan inventori untuk Universitas Harapan Bangsa. Panduan ini menjelaskan cara menggunakan fitur-fitur di panel admin untuk mencatat, melacak, dan melaporkan aset institusi.

---

- [Fitur Utama](#fitur-utama)
- [Pengguna Aplikasi](#pengguna-aplikasi)

<a name="fitur-utama"></a>
## Fitur Utama

- **Barang** — daftar aset per unit atau stok, dengan nomor seri, foto, dan status
- **Peminjaman** — pemindahan sementara aset ke lokasi lain
- **Audit barang** — pencatatan inspeksi fisik
- **Pemeliharaan** — tiket perawatan dan perbaikan
- **Pergerakan stok** — stok masuk/keluar untuk barang habis pakai
- **Data master** — organisasi, lokasi, departemen, ruangan, dan penyusutan
- **Referensi** — kategori, model, pabrikan, dan pemasok
- **Laporan** — ringkasan audit, pemeliharaan, peminjaman, depresiasi, dan riwayat aktivitas
- **Dasbor** — KPI, grafik, peringatan, dan pemindai barcode

<a name="pengguna-aplikasi"></a>
## Pengguna Aplikasi

SIRIS membedakan akses lewat peran. Saat ini pengguna aktif umumnya memiliki peran **Admin**.

| Peran | Kegiatan umum |
|-------|---------------|
| **Admin** | Mengelola data master, barang, laporan, dan pengguna |
| **Operator** | Mencatat peminjaman, audit, pergerakan stok, dan pemeliharaan harian *(setelah izin diatur)* |

> {info} Peran Operator
>
> Peran **Operator** sudah tersedia sebagai peran bawaan. Admin perlu membuka **Administrasi → Peran**, memilih **Operator**, lalu mencentang izin menu yang diperlukan. Tanpa izin, pengguna operator hanya dapat mengakses **Dasbor**. Lihat [Peran & Izin](/{{route}}/{{version}}/administrasi/peran-izin) untuk detail.

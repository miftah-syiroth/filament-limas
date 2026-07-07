# Transfer & Status

Tab **Transfer & status** pada halaman [Barang](/{{route}}/{{version}}/modul/item) mencatat perubahan **permanen** terhadap lokasi, departemen, ruangan, penanggung jawab, atau status barang. Setiap catatan tersimpan sebagai jejak audit yang tidak dapat diubah setelah dibuat.

---

- [Cara Membuka](#cara-membuka)
- [Konsep Transfer & Status](#konsep-transfer-dan-status)
- [Membuat Catatan](#membuat-catatan)
- [Melihat Riwayat](#melihat-riwayat)
- [Mengubah Catatan](#mengubah-catatan)
- [Menghapus Catatan](#menghapus-catatan)
- [Jenis Peristiwa](#jenis-peristiwa)
- [Hubungan dengan Modul Lain](#hubungan-dengan-modul-lain)
- [Langkah Selanjutnya](#langkah-selanjutnya)

<a name="cara-membuka"></a>
## Cara Membuka

1. Buka detail [Barang](/{{route}}/{{version}}/modul/item)
2. Klik tab **Transfer & status**

<a name="konsep-transfer-dan-status"></a>
## Konsep Transfer & Status

Setiap catatan di tab ini memiliki **jenis peristiwa** yang menentukan field apa yang diisi. Saat catatan disimpan, data barang diperbarui otomatis sesuai jenis peristiwanya.

**Kapan memakai transfer & status vs modul lain:**

| Situasi | Modul yang digunakan |
|---------|---------------------|
| Memindahkan laptop ke departemen lain secara permanen | Transfer & status — jenis **Transfer** |
| Mengganti penanggung jawab aset | Transfer & status — jenis **Penugasan** |
| Mengubah status barang menjadi Rusak | Transfer & status — jenis **Perubahan status** |
| Meminjamkan proyektor untuk acara 3 hari | [Peminjaman](/{{route}}/{{version}}/modul/peminjaman) |
| Mengubah jumlah stok tinta printer | [Pergerakan Stok](/{{route}}/{{version}}/modul/pergerakan-stok) |

Perubahan status juga dapat terjadi otomatis saat mencatat [Audit Barang](/{{route}}/{{version}}/modul/audit) atau menyelesaikan [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance) — dengan mekanisme jejak yang sama.

<a name="membuat-catatan"></a>
## Membuat Catatan

1. Buka barang → tab **Transfer & status**
2. Klik **Tambah transfer**
3. Pilih **Tipe event**:
   - **Transfer** — pindah lokasi, departemen, atau ruangan
   - **Penugasan** — ganti penanggung jawab
   - **Perubahan status** — ubah status barang
4. Isi field sesuai jenis peristiwa:
   - **Transfer:** isi minimal satu tujuan (**Lokasi ke**, **Departemen ke**, atau **Ruangan ke**) yang berbeda dari posisi saat ini
   - **Penugasan:** pilih **PJ ke** (penanggung jawab baru)
   - **Perubahan status:** pilih **Status ke** (tidak boleh sama dengan status saat ini)
5. Tambahkan **Catatan** jika perlu
6. Klik **Simpan**

Data barang (lokasi, departemen, ruangan, penanggung jawab, atau status) langsung diperbarui setelah penyimpanan.

<a name="melihat-riwayat"></a>
## Melihat Riwayat

Tabel di tab ini menampilkan semua perubahan sebelumnya: jenis peristiwa, nilai dari/ke, catatan, dan tanggal dibuat. Klik ikon mata pada baris untuk melihat detail lengkap dalam modal.

<a name="mengubah-catatan"></a>
## Mengubah Catatan

Riwayat transfer & status **tidak dapat diubah** setelah dibuat. Ini menjaga integritas jejak audit perpindahan aset.

Jika ada kesalahan, buat catatan korektif baru dengan data yang benar — misalnya transfer balik ke lokasi semula.

<a name="menghapus-catatan"></a>
## Menghapus Catatan

1. Buka tab **Transfer & status**
2. Centang satu atau lebih baris riwayat
3. Pilih aksi massal **Hapus**
4. Konfirmasi

> {warning} Penghapusan tidak mengembalikan data barang
>
> Menghapus riwayat **tidak otomatis** mengembalikan lokasi, penanggung jawab, atau status barang ke nilai sebelumnya. Riwayat bersifat catatan historis; gunakan catatan baru untuk koreksi jika diperlukan.

Riwayat yang dihapus menggunakan soft delete dan dapat dipulihkan lewat filter **Terhapus**.

Barang yang sudah memiliki riwayat transfer & status **tidak dapat dihapus** hingga riwayat tersebut dibersihkan.

<a name="jenis-peristiwa"></a>
## Jenis Peristiwa

| Jenis | Field yang berubah | Contoh |
|-------|-------------------|--------|
| **Transfer** | Lokasi, departemen, ruangan | Laptop dipindah dari Lab A ke Lab B |
| **Penugasan** | Penanggung jawab | Laptop diserahkan ke pegawai baru |
| **Perubahan status** | Status barang | Status diubah dari Aktif ke Rusak |

<a name="hubungan-dengan-modul-lain"></a>
## Hubungan dengan Modul Lain

| Modul | Hubungan |
|-------|----------|
| [Barang](/{{route}}/{{version}}/modul/item) | Setiap catatan milik satu barang; memperbarui data barang saat dibuat |
| [Audit Barang](/{{route}}/{{version}}/modul/audit) | Audit dapat menyertakan perubahan status opsional |
| [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance) | Tiket pemeliharaan dapat menyertakan perubahan status opsional |
| [Peminjaman](/{{route}}/{{version}}/modul/peminjaman) | Peminjaman bersifat sementara dan tidak tercatat di tab ini |

<a name="langkah-selanjutnya"></a>
## Langkah Selanjutnya

- [Peminjaman](/{{route}}/{{version}}/modul/peminjaman)
- [Barang](/{{route}}/{{version}}/modul/item)
- [Audit Barang](/{{route}}/{{version}}/modul/audit)

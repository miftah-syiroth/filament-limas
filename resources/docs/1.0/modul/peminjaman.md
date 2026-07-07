# Peminjaman

Modul **Peminjaman** digunakan untuk memindahkan aset **sementara** ke lokasi lain, lalu mengembalikannya ke posisi semula. Peminjaman tidak mengubah kepemilikan atau lokasi permanen barang — untuk itu gunakan [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status).

---

- [Cara Membuka](#cara-membuka)
- [Konsep Peminjaman](#konsep-peminjaman)
- [Membuat Peminjaman](#membuat-peminjaman)
- [Melihat Detail Peminjaman](#melihat-detail-peminjaman)
- [Mengubah Peminjaman](#mengubah-peminjaman)
- [Mengembalikan Barang](#mengembalikan-barang)
- [Menghapus Peminjaman](#menghapus-peminjaman)
- [Status dan Filter](#status-dan-filter)
- [Hubungan dengan Barang](#hubungan-dengan-barang)
- [Langkah Selanjutnya](#langkah-selanjutnya)

<a name="cara-membuka"></a>
## Cara Membuka

1. Masuk ke panel admin SIRIS
2. Klik **Peminjaman** di sidebar (grup **Fitur Utama**)

Akses modul ini memerlukan izin **Peminjaman**. Riwayat peminjaman per barang juga dapat dilihat dari tab **Peminjaman** pada halaman detail [Barang](/{{route}}/{{version}}/modul/item).

<a name="konsep-peminjaman"></a>
## Konsep Peminjaman

Satu transaksi peminjaman dapat mencakup **beberapa barang**. Setiap barang tercatat sebagai baris terpisah dengan kuantitas, tanggal keluar/masuk, dan kondisi fisik saat keluar dan masuk.

**Kapan memakai peminjaman:**

- Laptop dipinjam ke ruang rapat
- Proyektor dipakai untuk acara di gedung lain
- Kursi dipindah sementara untuk kegiatan

**Kapan tidak memakai peminjaman:**

- Perpindahan permanen lokasi atau departemen → [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status)
- Stok habis pakai masuk atau keluar → [Pergerakan Stok](/{{route}}/{{version}}/modul/pergerakan-stok)

Hanya barang berstatus **Aktif** dengan sisa kuantitas yang belum dipinjam yang dapat dimasukkan ke peminjaman baru.

<a name="membuat-peminjaman"></a>
## Membuat Peminjaman

Pembuatan peminjaman dilakukan lewat pemilihan barang dari tabel, bukan form repeater biasa.

1. Buka **Peminjaman** → klik **Tambah Peminjaman**
2. Pada tabel **Pilih Item**, cari dan filter barang yang dapat dipinjam
3. Centang baris barang yang ingin dipinjam
4. Isi **Jumlah** pada kolom kuantitas setiap baris (minimal 1, tidak boleh melebihi jumlah yang dapat dipinjam)
5. Pilih aksi massal **Buat Pinjaman Barang**
6. Pada modal yang muncul, isi:
   - **Tanggal peminjaman** dan **Batas peminjaman**
   - Minimal satu tujuan: **Lokasi tujuan**, **Departemen tujuan**, atau **Ruang tujuan**
   - **Catatan** (opsional — alasan, acara, PIC)
7. Konfirmasi — sistem membuat transaksi peminjaman dan mencatat posisi asal setiap barang

Setelah berhasil, Anda diarahkan ke halaman detail peminjaman.

<a name="melihat-detail-peminjaman"></a>
## Melihat Detail Peminjaman

Dari daftar peminjaman, klik transaksi untuk melihat:

- **Peminjam** (pengguna yang membuat transaksi)
- **Tujuan** — lokasi, departemen, ruang
- **Status** — Aktif atau Dikembalikan
- **Tanggal** — peminjaman, batas, dan pengembalian
- **Catatan**
- **Daftar item** — barang yang dipinjam beserta kuantitas, tanggal keluar/masuk, dan kondisi

<a name="mengubah-peminjaman"></a>
## Mengubah Peminjaman

1. Buka peminjaman dari daftar → klik **Ubah**
2. Perbarui field yang diizinkan:
   - **Tanggal peminjaman** dan **Batas peminjaman**
   - **Lokasi tujuan**, **Departemen tujuan**, **Ruang tujuan**
   - **Catatan**
3. Klik **Simpan**

**Field terkunci:**

- **Tanggal pengembalian** — tidak dapat diisi manual selama masih ada barang yang belum dikembalikan (belum ada tanggal masuk)

**Menambah barang ke peminjaman aktif:**

1. Buka detail atau halaman ubah peminjaman
2. Pada bagian **Item**, klik **Tambah item**
3. Pilih barang, isi jumlah, tanggal keluar, dan kondisi keluar
4. Simpan

Barang yang sudah ada di peminjaman yang sama tidak dapat ditambahkan ulang.

<a name="mengembalikan-barang"></a>
## Mengembalikan Barang

Pengembalian dilakukan **per baris barang**, bukan lewat satu tombol di level transaksi.

1. Buka peminjaman yang masih **Aktif**
2. Pada tabel **Item**, centang baris barang yang dikembalikan
3. Pilih aksi massal **Kembalikan Semua**
4. Isi **Tanggal masuk** dan **Kondisi masuk** (kondisi mengikuti skala audit: Sangat baik hingga Tidak layak)
5. Konfirmasi

Ketika **semua** baris sudah memiliki tanggal masuk, status peminjaman otomatis berubah menjadi **Dikembalikan** dan tanggal pengembalian diisi oleh sistem.

> {note} Peminjaman sudah dikembalikan
>
> Baris barang pada peminjaman berstatus **Dikembalikan** tidak dapat diubah lagi.

<a name="menghapus-peminjaman"></a>
## Menghapus Peminjaman

Penghapusan dapat dilakukan dari halaman **Ubah** (tombol hapus di header) atau lewat aksi massal di daftar peminjaman.

- Penghapusan biasa (**soft delete**) — transaksi disembunyikan tetapi dapat dipulihkan lewat filter **Terhapus**
- **Hapus permanen** — menghapus transaksi beserta semua baris peminjaman terkait

Pastikan data peminjaman sudah tidak diperlukan sebelum menghapus permanen.

<a name="status-dan-filter"></a>
## Status dan Filter

| Status | Arti |
|--------|------|
| **Aktif** | Masih ada barang yang dipinjam |
| **Dikembalikan** | Semua barang sudah kembali |

**Filter berguna di daftar:**

- **Status** — Aktif / Dikembalikan
- **Terlambat** — peminjaman yang melewati batas tanggal (belum dikembalikan, atau dikembalikan setelah batas)

Peminjaman terlambat ditandai ikon peringatan pada kolom **Batas peminjaman**.

<a name="hubungan-dengan-barang"></a>
## Hubungan dengan Barang

- Setiap baris peminjaman merujuk ke satu [Barang](/{{route}}/{{version}}/modul/item)
- Kuantitas yang dapat dipinjam = kuantitas barang dikurangi jumlah yang sedang dipinjam (aktif)
- Riwayat peminjaman per barang tersedia di tab **Peminjaman** pada detail barang (baca saja, dengan tautan ke transaksi)

<a name="langkah-selanjutnya"></a>
## Langkah Selanjutnya

- [Barang](/{{route}}/{{version}}/modul/item)
- [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status)
- [Audit Barang](/{{route}}/{{version}}/modul/audit)

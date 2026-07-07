# Tabel Data

Hampir semua modul di panel admin SIRIS menampilkan data dalam bentuk **tabel**. Halaman ini menjelaskan cara memakai fitur tabel yang tersedia di aplikasi — pencarian, filter, pengurutan, kolom yang dapat disembunyikan, paginasi, data terhapus, serta aksi per baris dan aksi massal.

---

- [Bagian-bagian Tabel](#bagian-bagian-tabel)
- [Pencarian](#pencarian)
- [Filter](#filter)
- [Pengurutan Kolom](#pengurutan-kolom)
- [Menyembunyikan Kolom](#menyembunyikan-kolom)
- [Paginasi](#paginasi)
- [Data Terhapus](#data-terhapus)
- [Aksi per Baris](#aksi-per-baris)
- [Aksi Massal](#aksi-massal)
- [Contoh: Tabel Barang](#contoh-tabel-barang)
- [Contoh: Tabel Peminjaman](#contoh-tabel-peminjaman)

<a name="bagian-bagian-tabel"></a>
## Bagian-bagian Tabel

Setiap halaman daftar modul umumnya memiliki area berikut:

| Area | Fungsi |
|------|--------|
| **Toolbar atas** | Kotak pencarian, tombol filter, impor/ekspor (jika tersedia), dan pengatur kolom |
| **Header kolom** | Nama kolom; kolom yang dapat diurutkan menampilkan ikon panah saat diklik |
| **Baris data** | Isi setiap record; status ditampilkan sebagai badge, peringatan sebagai ikon |
| **Aksi baris** | Tombol **Lihat** dan **Ubah** di ujung kanan setiap baris |
| **Footer** | Navigasi halaman dan pemilih jumlah baris per halaman |
| **Toolbar bawah** | Menu aksi massal setelah satu atau lebih baris dicentang |

<a name="pencarian"></a>
## Pencarian

SIRIS memiliki dua jenis pencarian yang saling **independen**:

### Pencarian di tabel

Kotak pencarian di atas tabel hanya mencari kolom tertentu yang dikonfigurasi untuk modul tersebut. Tidak semua kolom ikut dicari.

| Modul | Kolom yang dapat dicari |
|-------|-------------------------|
| **Barang** | Nomor seri, model, nama barang |
| **Peminjaman** | Tidak tersedia — gunakan filter |
| **Referensi** (pabrikan, organisasi, dll.) | Umumnya nama, email, atau URL |

Ketik kata kunci lalu tunggu hasil tabel diperbarui.

### Pencarian global

Kotak pencarian di **header panel** (bagian atas layar) mencari lintas modul yang diaktifkan. Saat ini, modul **Barang** mendukung pencarian global untuk nomor seri, nama, model, lokasi, departemen, ruangan, dan penanggung jawab — maksimal 10 hasil per pencarian.

> {info} Pencarian global dan pencarian di tabel bekerja terpisah. Hasil keduanya tidak saling memengaruhi.

<a name="filter"></a>
## Filter

Klik ikon **filter** (corong) di toolbar atas untuk membuka panel filter. Pada modul **Barang** dan **Peminjaman**, panel filter disusun dalam tiga kolom.

### Filter pilihan (SelectFilter)

Jenis filter paling umum di SIRIS:

- **Pilihan ganda** — centang lebih dari satu nilai, misalnya beberapa status sekaligus
- **Filter relasi** — pilih berdasarkan kategori, model, lokasi, departemen, ruangan, atau pemasok; daftar dapat dicari di dalam dropdown

Contoh di **Barang**: filter status, kategori, pabrikan, model, departemen, lokasi, ruangan, dan pemasok.

Contoh di **Peminjaman**: filter status (Aktif / Dikembalikan).

### Filter ya/tidak (TernaryFilter)

Hanya tersedia di modul **Peminjaman**:

| Opsi | Arti |
|------|------|
| **Semua** | Tanpa filter keterlambatan |
| **Ya** | Peminjaman terlambat — batas lewat dan belum dikembalikan, atau dikembalikan setelah batas |
| **Tidak** | Peminjaman tidak terlambat |

Kolom **Batas peminjaman** menampilkan ikon peringatan pada baris yang terlambat, terlepas dari filter yang aktif.

<a name="pengurutan-kolom"></a>
## Pengurutan Kolom

Klik header kolom yang dapat diurutkan untuk mengubah urutan naik atau turun. Klik lagi untuk membalik arah.

**Urutan bawaan** saat halaman pertama dibuka:

| Modul | Urutan bawaan |
|-------|---------------|
| **Barang** | Terbaru dibuat (paling atas) |
| **Peminjaman** | Tanggal peminjaman terbaru (paling atas) |

Kolom yang dapat diurutkan di **Peminjaman**: tanggal peminjaman, batas peminjaman, tanggal pengembalian, dibuat pada, dihapus pada.

Di **Barang**, kolom **Dihapus pada** dapat diurutkan secara manual setelah kolom tersebut ditampilkan.

<a name="menyembunyikan-kolom"></a>
## Menyembunyikan Kolom

Klik tombol **kolom** di toolbar untuk membuka daftar centang. Centang atau hapus centang untuk menampilkan atau menyembunyikan kolom opsional.

Beberapa kolom disembunyikan secara bawaan agar tabel tetap ringkas:

**Barang** — tersembunyi bawaan: tipe, nama, departemen, ruangan, pemasok, tanggal pembelian, harga pembelian, tanggal EOL, masa garansi, pelacakan individu. Kolom **Lokasi** tampil bawaan.

**Peminjaman** — tersembunyi bawaan: lokasi tujuan, departemen tujuan, ruangan tujuan, dibuat pada, dihapus pada.

Preferensi kolom berlaku selama sesi Anda berada di halaman tersebut.

<a name="paginasi"></a>
## Paginasi

Tabel memuat data per halaman agar tetap responsif. Di bagian bawah tabel:

- Gunakan tombol **sebelumnya** / **berikutnya** untuk berpindah halaman
- Pilih **jumlah baris per halaman** sesuai kebutuhan

> {info} Pengecualian
>
> Tabel di dalam halaman detail — misalnya daftar item pada peminjaman — kadang menampilkan semua baris sekaligus tanpa paginasi.

<a name="data-terhapus"></a>
## Data Terhapus

Modul yang mendukung **soft delete** menyediakan filter data terhapus, termasuk **Barang**, **Peminjaman**, **Organisasi**, **Departemen**, serta tab riwayat di detail barang.

Filter ini memiliki tiga opsi:

| Opsi | Keterangan |
|------|------------|
| Tanpa data terhapus | Bawaan — hanya data aktif |
| Dengan data terhapus | Menampilkan data aktif dan terhapus |
| Hanya data terhapus | Hanya data yang sudah dihapus |

Aktifkan kolom **Dihapus pada** lewat pengatur kolom untuk melihat kapan data dihapus.

<a name="aksi-per-baris"></a>
## Aksi per Baris

Setiap baris memiliki dua aksi di ujung kanan:

| Tombol | Fungsi |
|--------|--------|
| **Lihat** | Membuka halaman detail (hanya baca) |
| **Ubah** | Membuka halaman edit |

Tombol hanya tampil jika peran Anda memiliki izin yang sesuai.

<a name="aksi-massal"></a>
## Aksi Massal

Centang satu atau lebih baris, lalu pilih aksi dari menu di toolbar bawah:

| Aksi | Keterangan |
|------|------------|
| **Hapus** | Memindahkan data ke tempat sampah (soft delete) |
| **Pulihkan** | Mengembalikan data yang sudah dihapus |
| **Hapus permanen** | Menghapus data sepenuhnya dari database |

Sistem memeriksa izin **per baris** — baris yang tidak boleh Anda ubah akan dilewati.

> {warning} Hapus permanen tidak dapat dibatalkan. Pastikan data yang dipilih memang tidak diperlukan lagi.

**Khusus Peminjaman:** aksi hapus permanen juga menghapus seluruh item peminjaman yang terkait.

<a name="contoh-tabel-barang"></a>
## Contoh: Tabel Barang

Modul [Barang](/{{route}}/{{version}}/modul/item) adalah contoh tabel paling lengkap di SIRIS.

### Kolom utama

| Kolom | Keterangan |
|-------|------------|
| Gambar | Thumbnail barang |
| Nomor seri | Dapat dicari |
| Kategori, Pabrikan | Dari data model |
| Model | Dapat diklik menuju detail model |
| Status | Badge warna |
| Kuantitas | Jumlah stok atau unit |
| Dapat dipinjam | Sisa yang belum dipinjam |

### Filter

Sembilan filter pilihan (status, kategori, pabrikan, model, departemen, lokasi, ruangan, pemasok) ditambah filter **data terhapus**.

### Aksi tambahan

Di toolbar atas tersedia **Ekspor** dan **Impor** spreadsheet.

### Aksi massal

Hapus, pulihkan, dan hapus permanen — tersedia setelah mencentang baris.

<a name="contoh-tabel-peminjaman"></a>
## Contoh: Tabel Peminjaman

Modul [Peminjaman](/{{route}}/{{version}}/modul/peminjaman) menampilkan ringkasan setiap transaksi peminjaman.

### Kolom utama

| Kolom | Keterangan |
|-------|------------|
| Tanggal peminjaman | Dapat diurutkan |
| Batas peminjaman | Dapat diurutkan; ikon peringatan jika terlambat |
| Tanggal pengembalian | Dapat diurutkan |
| Item | Jumlah baris item dalam peminjaman |
| Status | Badge: Aktif atau Dikembalikan |

Kolom lokasi, departemen, dan ruangan tujuan tersedia lewat pengatur kolom.

### Filter

- **Status** — pilihan ganda (Aktif / Dikembalikan)
- **Terlambat** — Semua / Ya / Tidak
- **Data terhapus**

### Aksi massal

Hapus, hapus permanen (termasuk item terkait), dan pulihkan.

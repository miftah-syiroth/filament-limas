# Dasbor

**Dasbor** adalah halaman pertama yang Anda lihat setelah masuk ke panel admin SIRIS. Halaman ini menampilkan ringkasan KPI, peringatan operasional, grafik inventori, serta pintasan untuk menemukan barang dan membuka modul terkait — tanpa perlu membuka menu satu per satu.

---

- [Cara Membuka](#cara-membuka)
- [Tata Letak](#tata-letak)
- [Pemindai Barcode](#pemindai-barcode)
- [Akun Pengguna](#akun-pengguna)
- [Peringatan](#peringatan)
- [Inventori](#inventori)
- [Audit dan Pemeliharaan](#audit-dan-pemeliharaan)
- [Stok](#stok)
- [Keuangan dan Depresiasi](#keuangan-dan-depresiasi)
- [Data Master](#data-master)
- [Aktivitas Terbaru](#aktivitas-terbaru)
- [Hak Akses Widget](#hak-akses-widget)

<a name="cara-membuka"></a>
## Cara Membuka

1. Masuk ke panel admin SIRIS
2. Klik **Dasbor** di sidebar (menu paling atas di bagian **Antarmuka**)

Dasbor adalah halaman bawaan setelah login. Untuk gambaran umum fitur SIRIS, lihat [Ringkasan](/{{route}}/{{version}}/overview).

<a name="tata-letak"></a>
## Tata Letak

Widget dasbor disusun dari atas ke bawah dalam urutan tetap. Tata letak kolom menyesuaikan lebar layar:

| Lebar layar | Kolom |
|-------------|-------|
| Ponsel | 1 kolom |
| Tablet | 2 kolom |
| Desktop lebar | 4 kolom |

**Pemindai Barcode** dan **Akun Pengguna** melebar dua kolom di layar desktop sehingga lebih mudah dipakai. Widget lainnya mengisi satu atau seluruh lebar baris sesuai jenisnya (kartu ringkasan, grafik, atau tabel).

<a name="pemindai-barcode"></a>
## Pemindai Barcode

Widget di bagian paling atas dasbor untuk mencari barang dengan cepat.

**Cara memakai:**

1. Ketik **nomor seri** (8 karakter) pada kolom input, atau klik **Scan barcode** untuk memindai lewat kamera perangkat
2. Sistem menormalisasi input ke huruf besar; pencarian berjalan otomatis setelah 8 karakter terisi
3. Jika barang ditemukan, modal **Detail item** menampilkan nomor seri dan status
4. Klik **Detail** untuk membuka halaman lengkap barang tersebut

Jika barcode tidak valid atau barang tidak ditemukan, notifikasi peringatan akan muncul di pojok layar.

> {info} Nomor seri
>
> Setiap barang dengan pelacakan individu memiliki nomor seri unik 8 karakter. Lihat [Barang](/{{route}}/{{version}}/modul/item) untuk detail pencatatan inventori.

<a name="akun-pengguna"></a>
## Akun Pengguna

Widget di samping pemindai barcode yang menampilkan nama dan avatar akun Anda yang sedang login. Dari sini Anda dapat membuka profil atau keluar dari aplikasi.

<a name="peringatan"></a>
## Peringatan

Bagian **Peringatan** menampilkan lima kartu KPI yang diperbarui otomatis setiap ±60 detik. Klik salah satu kartu untuk membuka daftar terfilter di modul terkait.

| Kartu | Keterangan |
|-------|------------|
| **Maintenance Belum Selesai** | Jumlah tiket pemeliharaan berstatus dilaporkan dan dalam proses |
| **Audit Jatuh Tempo** | Audit yang sudah terlambat ditambah yang jatuh tempo dalam 7 hari ke depan |
| **Stok di bawah minimum** | Model barang dengan stok inventori di bawah batas minimum |
| **Item bermasalah** | Barang berstatus rusak, sedang didiagnosis, sedang diperbaiki, atau tidak dapat diperbaiki |
| **Item hilang / dicuri** | Jumlah barang berstatus hilang atau dicuri |

> {warning} Perhatian segera
>
> Kartu dengan warna merah atau oranye menandakan kondisi yang perlu ditindaklanjuti. Prioritaskan audit terlambat dan stok di bawah minimum agar operasional tidak terganggu.

<a name="inventori"></a>
## Inventori

Bagian inventori terdiri dari kartu ringkasan, grafik, dan tabel rincian.

### Kartu ringkasan

Bagian **Inventori** menampilkan empat angka utama:

| Kartu | Keterangan |
|-------|------------|
| **Total Stok** | Jumlah unit seluruh barang inventori aktif, dengan jumlah item terdaftar di bawahnya |
| **Item mendekati EOL** | Barang yang masa pakainya (end of life) berakhir dalam 90 hari ke depan |
| **Garansi hampir habis** | Barang yang masa garansinya berakhir dalam 30 hari ke depan |
| **Item Tanpa Audit** | Barang yang belum pernah diaudit padahal modelnya mewajibkan audit berkala |

Klik kartu untuk membuka daftar **Barang**.

### Grafik

| Grafik | Keterangan |
|--------|------------|
| **Item per Status** | Diagram donat distribusi status barang inventori aktif |
| **Item per Kategori** | Distribusi per jenis kategori: aset, aksesori, consumable, lisensi |
| **Item per Lokasi** | Sepuluh lokasi dengan barang terbanyak (grafik bar horizontal) |
| **Item per Departemen** | Sepuluh departemen dengan barang terbanyak (grafik bar horizontal) |

Barang tanpa lokasi atau departemen dikelompokkan sebagai **Tanpa lokasi** atau **Tanpa departemen**.

### Tabel

**Item terdekat EOL** — daftar hingga 10 barang yang paling cepat mencapai akhir masa pakai, menampilkan nomor seri, model, tanggal EOL, dan lokasi. Klik nomor seri untuk membuka detail barang.

**Garansi segera berakhir** — daftar hingga 10 barang yang garansinya berakhir dalam 30 hari, menampilkan nomor seri, model, tanggal berakhir, dan pemasok.

<a name="audit-dan-pemeliharaan"></a>
## Audit dan Pemeliharaan

Bagian **Audit & Pemeliharaan** menampilkan empat kartu:

| Kartu | Keterangan |
|-------|------------|
| **Audit Bulan Ini** | Jumlah audit yang dicatat bulan berjalan, dengan perbandingan persentase terhadap bulan lalu |
| **Hasil Audit Bermasalah** | Audit 30 hari terakhir yang menghasilkan rekomendasi perlu pemeliharaan, penggantian, atau disposal |
| **Lokasi Tidak Terverifikasi** | Audit di mana lokasi fisik barang belum dikonfirmasi sesuai catatan |
| **Biaya Maintenance Bulan Ini** | Total biaya tiket pemeliharaan yang sudah selesai bulan ini |

Klik kartu untuk membuka modul [Audit Barang](/{{route}}/{{version}}/modul/audit) atau [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance).

<a name="stok"></a>
## Stok

Bagian stok mencakup ringkasan pergerakan harian, tabel consumable kritis, dan grafik penggunaan.

### Pergerakan stok hari ini

Tiga kartu di bagian **Pergerakan stok hari ini**:

| Kartu | Keterangan |
|-------|------------|
| **Masuk** | Jumlah transaksi stok masuk hari ini |
| **Keluar** | Jumlah transaksi stok keluar hari ini |
| **Penyesuaian** | Jumlah penyesuaian stok hari ini |

Perhitungan ini hanya mencakup barang **stok massal** (bukan pelacakan per unit).

### Consumable di bawah minimum

Tabel **Consumable di bawah minimum** menampilkan model consumable yang stoknya sama dengan atau di bawah batas minimum, beserta kolom stok, minimum, dan selisih. Klik nama model untuk membuka detailnya.

### Top consumable terpakai

Grafik **Top consumable terpakai (30 hari)** menampilkan sepuluh model consumable dengan pengeluaran stok terbanyak dalam 30 hari terakhir.

Pelajari cara mencatat pergerakan stok di [Pergerakan Stok](/{{route}}/{{version}}/modul/pergerakan-stok).

<a name="keuangan-dan-depresiasi"></a>
## Keuangan dan Depresiasi

Bagian **Keuangan & depresiasi** menampilkan tiga kartu:

| Kartu | Keterangan |
|-------|------------|
| **Total nilai pembelian (aktif)** | Akumulasi harga pembelian seluruh barang inventori aktif |
| **Total nilai setelah depresiasi** | Nilai buku saat ini setelah penyusutan, dengan persentase penyusutan di bawahnya |
| **Mendekati nilai minimum depresiasi** | Jumlah barang yang nilai bukunya sudah mendekati batas minimum penyusutan |

Di bawah kartu tersebut, grafik **Penyusutan bulanan** menampilkan tren penyusutan dalam 12 bulan terakhir.

> {info} Perhitungan depresiasi
>
> Hanya barang yang memiliki harga pembelian dan model dengan skema penyusutan yang dihitung. Atur skema penyusutan di [Penyusutan](/{{route}}/{{version}}/master/penyusutan).

<a name="data-master"></a>
## Data Master

Bagian **Master data** menampilkan jumlah referensi utama:

| Kartu | Menuju ke |
|-------|-----------|
| **Model** | Daftar model barang |
| **Kategori** | Daftar kategori |
| **Supplier** | Daftar pemasok |
| **Pabrikan** | Daftar pabrikan |

Klik kartu untuk langsung membuka daftar terkait.

<a name="aktivitas-terbaru"></a>
## Aktivitas Terbaru

Tabel **Aktivitas terbaru** di bagian bawah dasbor menampilkan lima log perubahan terakhir di sistem, dengan kolom:

- **Pengguna** yang melakukan aksi
- **Peristiwa** (buat, ubah, hapus, dan sejenisnya)
- **Jenis data** yang terpengaruh
- **Deskripsi** singkat
- **Waktu** relatif (misalnya "5 menit yang lalu")

Klik baris untuk membuka daftar lengkap log aktivitas.

<a name="hak-akses-widget"></a>
## Hak Akses Widget

Tidak semua widget selalu tampil bagi setiap pengguna. Visibilitas bergantung pada **izin** yang diberikan melalui peran:

| Widget | Izin minimal |
|--------|-------------|
| Kartu **Inventori** | Lihat daftar Barang |
| **Pergerakan stok hari ini** | Lihat daftar Barang |
| **Keuangan & depresiasi** | Lihat daftar Barang |
| **Master data** | Lihat salah satu dari: Model, Kategori, Pemasok, atau Pabrikan |

Widget **Peringatan**, grafik inventori, tabel EOL/garansi, **Audit & Pemeliharaan**, tabel consumable, grafik top consumable, **Penyusutan bulanan**, dan **Aktivitas terbaru** umumnya tampil bagi pengguna yang sudah masuk ke panel admin.

> {warning} Akses terbatas
>
> Tanpa izin modul terkait, widget tertentu disembunyikan. Pengguna dengan akses terbatas mungkin hanya melihat pemindai barcode, akun pengguna, peringatan, dan grafik umum. Admin dapat mengatur izin di [Peran & Izin](/{{route}}/{{version}}/administrasi/peran-izin).

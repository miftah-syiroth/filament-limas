# Barang

Modul **Barang** adalah pusat inventori SIRIS. Di sini Anda mencatat setiap aset — baik per unit (laptop, proyektor) maupun stok massal (consumable).

## Siapa yang Bisa Mengakses

Operator dan admin dengan izin **Barang** dapat melihat dan mencatat data. Izin buat, ubah, atau hapus bergantung peran Anda.

## Cara Membuka

Sidebar → **Barang** (menu utama, bagian atas)

## Melihat Daftar Barang

1. Klik **Barang** di sidebar
2. Gunakan **pencarian** dan **filter** di atas tabel untuk mempersempit hasil
3. Klik baris atau tombol **Lihat** untuk membuka detail

Anda juga dapat mencari barang dari **kotak pencarian global** di header panel (nomor seri, nama, model, lokasi, dll.).

## Menambah Barang Baru

1. Klik **Tambah Barang**
2. Isi informasi utama:
   - **Kategori** dan **Model**
   - **Lokasi**, **Departemen**, **Ruangan**
   - **Status** (bawaan: Aktif)
   - **Penanggung jawab** (opsional)
3. Isi **Informasi pembelian** jika perlu menghitung depresiasi (tanggal & harga pembelian)
4. Unggah **Gambar** jika ada
5. Atur **Kuantitas** sesuai jenis barang:
   - **Pelacakan individu** — setiap unit menjadi baris terpisah dengan nomor seri unik
   - **Stok massal** — satu record dengan jumlah stok
6. Klik **Simpan**

> {note} Kategori consumable
>
> Barang kategori consumable selalu dicatat sebagai stok massal, bukan per unit.

## Melihat Detail Barang

Halaman detail menampilkan:

- **Nomor seri** dan kode barcode/QR
- Spesifikasi (model, kategori, pabrikan)
- Lokasi, departemen, ruangan, penanggung jawab
- Informasi pembelian dan nilai depresiasi
- Tanggal audit berikutnya dan masa garansi

## Mengubah Barang

1. Buka detail barang → klik **Ubah**
2. Perbarui field yang diperlukan
3. Simpan perubahan

## Status Barang

| Status | Keterangan |
|--------|------------|
| Aktif | Beroperasi normal, masuk perhitungan inventori |
| Sedang didiagnosis | Sedang diperiksa masalahnya |
| Sedang diperbaiki | Dalam proses perbaikan |
| Rusak | Tidak berfungsi |
| Tidak dapat diperbaiki | Rusak permanen |
| Hilang / Dicuri | Tidak ada di lokasi, dikeluarkan dari inventori aktif |
| Diarsipkan / Dimusnahkan | Tidak lagi digunakan |

## Tab pada Detail Barang

| Tab | Kegunaan |
|-----|----------|
| **Stok** | Catat stok masuk/keluar (consumable) — lihat [Pergerakan Stok](/{{route}}/{{version}}/modul/pergerakan-stok) |
| **Pemeliharaan** | Riwayat dan tambah tiket pemeliharaan |
| **Audit** | Riwayat dan tambah audit fisik |
| **Transfer & status** | Pindah lokasi permanen atau ganti penanggung jawab |
| **Peminjaman** | Riwayat peminjaman item ini |

## Impor & Ekspor

- **Impor** — unggah spreadsheet dari tombol **Impor** di daftar barang
- **Ekspor** — unduh data dari tombol **Ekspor**

Lihat [Impor & Ekspor](/{{route}}/{{version}}/modul/impor-ekspor) untuk detail.

## Langkah Selanjutnya

- [Peminjaman](/{{route}}/{{version}}/modul/peminjaman)
- [Audit Barang](/{{route}}/{{version}}/modul/audit)
- [Data Master](/{{route}}/{{version}}/modul/data-master)

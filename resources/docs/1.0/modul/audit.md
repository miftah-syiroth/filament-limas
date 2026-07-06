# Audit Barang

Modul **Audit barang** mencatat hasil inspeksi fisik terhadap aset — kondisi barang, kecocokan lokasi, dan jadwal audit berikutnya.

## Siapa yang Bisa Mengakses

Operator dan admin dengan izin **Audit barang**.

## Cara Membuka

- Sidebar → grup **Laporan** → **Audit barang**
- Atau dari tab **Audit** pada halaman detail barang

## Mencatat Audit dari Daftar Laporan

1. Buka **Audit barang** di grup Laporan
2. Klik **Tambah** (jika tersedia) atau buat audit dari halaman barang
3. Pilih **barang** yang diaudit
4. Isi:
   - **Tanggal audit**
   - **Kondisi** — Sangat baik, Baik, Cukup, Buruk, Tidak layak
   - **Hasil** — OK, Perlu pemeliharaan, Perlu penggantian, Buang
   - **Lokasi sesuai** — centang jika barang ada di lokasi yang tercatat
   - **Audit berikutnya** — jadwal inspeksi berikutnya
   - **Catatan**
5. Simpan

## Mencatat Audit dari Halaman Barang

1. Buka detail barang → tab **Audit**
2. Klik **Tambah audit**
3. Isi form yang sama seperti di atas

## Kondisi & Hasil Audit

**Kondisi fisik:**

| Pilihan | Arti |
|---------|------|
| Sangat baik | Kondisi prima |
| Baik | Layak pakai normal |
| Cukup | Perlu perhatian |
| Buruk | Rusak ringan |
| Tidak layak | Tidak dapat digunakan |

**Hasil audit:**

| Pilihan | Tindak lanjut |
|---------|---------------|
| OK | Tidak perlu tindakan |
| Perlu pemeliharaan | Buat tiket pemeliharaan |
| Perlu penggantian | Pertimbangkan pengadaan baru |
| Buang | Ajukan pemusnahan |

## Peringatan di Dasbor

Barang dengan **audit berikutnya** yang sudah lewat atau dalam 7 hari muncul sebagai peringatan di dasbor.

## Ekspor

Dari daftar audit barang, gunakan tombol **Ekspor** untuk mengunduh data ke spreadsheet.

## Langkah Selanjutnya

- [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance)
- [Barang](/{{route}}/{{version}}/modul/inventori)
- [Laporan](/{{route}}/{{version}}/modul/laporan)

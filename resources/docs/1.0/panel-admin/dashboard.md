# Dasbor

Dasbor adalah halaman pertama setelah login. Di sini Anda melihat ringkasan kondisi inventori dan peringatan yang perlu ditindaklanjuti.

## Cara Membuka

Klik **Dasbor** di sidebar (biasanya halaman default saat masuk ke `/admin`).

## Peringatan & KPI

Bagian atas dasbor menampilkan kartu peringatan, antara lain:

| Peringatan | Arti |
|------------|------|
| Pemeliharaan belum selesai | Ada tiket perawatan yang masih terbuka |
| Audit jatuh tempo | Barang perlu diinspeksi (sudah lewat atau dalam 7 hari) |
| Stok kritis | Stok consumable di bawah batas minimum |
| Barang bermasalah | Status rusak, sedang diperbaiki, atau sejenisnya |
| Barang hilang/dicuri | Barang berstatus hilang atau dicuri |

Klik kartu peringatan jika tersedia tautan ke daftar terkait.

## Ringkasan Inventori

- **Total barang** dan nilai pembelian keseluruhan
- **Grafik status** — distribusi barang per status (aktif, rusak, dll.)
- **Grafik kategori, lokasi, departemen** — sebaran aset
- **Tabel mendekati kadaluarsa** — barang yang hampir mencapai akhir masa pakai
- **Tabel garansi berakhir** — barang dengan masa garansi yang akan habis

## Audit & Pemeliharaan

Ringkasan jumlah audit dan pemeliharaan, plus grafik pemeliharaan per jenis.

## Stok

- Pergerakan stok hari ini
- Consumable dengan stok di bawah minimum
- Grafik consumable paling aktif

## Keuangan

- Perbandingan nilai pembelian vs nilai buku (setelah penyusutan)
- Grafik tren penyusutan bulanan
- Barang mendekati nilai residu minimum

## Data Master

Ringkasan jumlah model, kategori, pemasok, dan pabrikan yang terdaftar.

## Riwayat Aktivitas

Tabel 10 aktivitas terbaru — siapa melakukan apa dan kapan. Berguna untuk audit internal.

## Pemindai Barcode

Widget pemindai barcode di dasbor memungkinkan Anda memindai **nomor seri** barang (8 karakter) untuk langsung membuka halaman detail barang tersebut.

### Cara menggunakan

1. Klik kolom pemindai di widget
2. Arahkan pemindai barcode ke label barang, atau ketik nomor seri manual
3. Halaman detail barang terbuka otomatis

## Laporan Depresiasi Lengkap

Untuk laporan nilai buku yang lebih detail, buka menu **Barang depresiasi** di grup Laporan. Dari sana Anda dapat mengekspor data.

## Langkah Selanjutnya

- [Barang](/{{route}}/{{version}}/modul/inventori)
- [Laporan](/{{route}}/{{version}}/modul/laporan)

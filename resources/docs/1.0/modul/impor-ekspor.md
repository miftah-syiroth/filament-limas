# Impor & Ekspor

SIRIS mendukung pengunggahan dan pengunduhan data dalam format spreadsheet (`.xlsx`) untuk mempercepat input data massal.

## Siapa yang Bisa Mengakses

Admin dan operator dengan izin impor/ekspor pada menu terkait.

## Impor Data

### Langkah umum

1. Buka menu resource yang mendukung impor (misalnya **Barang**, **Kategori**, **Model**)
2. Klik tombol **Impor**
3. Unduh **template** jika tersedia
4. Isi spreadsheet sesuai format kolom template
5. Unggah file
6. Tinjau pratinjau dan konfirmasi

### Menu yang mendukung impor

| Menu | Keterangan |
|------|------------|
| Kategori | Data kategori barang |
| Pabrikan | Data produsen |
| Model | Template produk |
| Barang | Data aset inventori |

> {primary} Urutan impor
>
> Impor **kategori → pabrikan → model** terlebih dahulu sebelum mengimpor barang, karena barang memerlukan referensi model dan lokasi yang sudah ada.

## Ekspor Data

### Langkah umum

1. Buka menu atau laporan yang mendukung ekspor
2. Klik tombol **Ekspor**
3. Pilih kolom yang ingin disertakan (jika diminta)
4. Unduh file hasil

### Menu yang mendukung ekspor

| Menu / Laporan | Data |
|----------------|------|
| Barang | Seluruh data inventori |
| Audit barang | Data audit |
| Pemeliharaan | Tiket pemeliharaan |
| Barang dipinjam | Detail item peminjaman |
| Barang depresiasi | Laporan nilai buku |

## Tips

- Pastikan format tanggal dan angka mengikuti template
- Periksa kembali data sebelum konfirmasi impor — kesalahan massal sulit diperbaiki
- Simpan file ekspor sebagai cadangan berkala

## Langkah Selanjutnya

- [Data Master](/{{route}}/{{version}}/modul/data-master)
- [Barang](/{{route}}/{{version}}/modul/inventori)

# Satuan

Menu **Satuan** menstandarkan cara mengukur kuantitas barang di katalog produk — misalnya pcs, unit, rim, atau liter. Contoh: laptop dihitung per **unit**, kertas per **rim**, tinta per **botol**. Data ini dipakai saat mendefinisikan **Model** di grup **Referensi** dan memastikan stok serta laporan konsisten. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi) dan [Ringkasan](/{{route}}/{{version}}/overview).

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Model** | Setiap model barang dapat memiliki satu satuan |

**Catatan:** Satuan tidak terikat hierarki **Organisasi**, **Lokasi**, atau **Departemen**. Isi satuan kapan saja sebelum membuat **Model** di grup **Referensi**.

<a name="menambah-data"></a>
## Menambah Data

Halaman menampilkan daftar satuan dalam satu layar kelola (tanpa halaman tambah terpisah).

1. Klik tombol **Tambah** di bagian atas tabel
2. Isi **Nama** — wajib (mis. `pcs`, `unit`, `rim`)
3. Klik **Simpan**

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada baris satuan yang ingin diedit, klik **Ubah**
2. Perbarui **Nama**
3. Klik **Simpan**

Anda juga dapat klik **Lihat** untuk melihat detail satuan sebelum mengubah.

<a name="menghapus-data"></a>
## Menghapus Data

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Satuan yang masih dipakai oleh **Model** sebaiknya tidak dihapus. Periksa kolom **Jumlah Model** di tabel sebelum menghapus.

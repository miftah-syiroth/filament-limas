# Penyusutan

Menu **Penyusutan** mendefinisikan bagaimana nilai aset berkurang seiring masa pakai (depresiasi). Setiap aturan mencakup masa manfaat, batas nilai terendah (persentase residu), dan metode perhitungan. SIRIS memakai aturan ini untuk nilai buku **Barang** pada dasbor dan laporan depresiasi; aturan dihubungkan ke **Model** di grup **Referensi**. Lihat [Inisialisasi](/{{route}}/{{version}}/memulai/inisialisasi) — pengisian penyusutan dianjurkan jika institusi perlu menghitung depresiasi.

---

- [Relasi Data](#relasi-data)
- [Menambah Data](#menambah-data)
- [Mengubah Data](#mengubah-data)
- [Menghapus Data](#menghapus-data)

<a name="relasi-data"></a>
## Relasi Data

| Terhubung ke | Hubungan |
|--------------|----------|
| **Model** | Setiap model dapat dihubungkan ke satu aturan penyusutan |
| **Barang** | Nilai buku barang dihitung dari aturan penyusutan pada model-nya |

**Catatan:** Penyusutan tidak terikat hierarki organisasi. Isi aturan ini sebelum atau bersamaan dengan pembuatan **Model** yang memerlukan perhitungan depresiasi.

<a name="menambah-data"></a>
## Menambah Data

1. Klik **Tambah penyusutan**
2. Isi field berikut:
   - **Nama** — wajib, nama aturan (mis. *Laptop 4 tahun*)
   - **Masa manfaat (bulan)** — wajib, lama penyusutan dalam bulan
   - **Batas Nilai Terendah (%)** — wajib, persentase nilai residu minimum (lihat teks bantuan di form)
   - **Metode** — wajib, saat ini tersedia **Garis lurus**
   - **Catatan** — opsional
3. Klik **Simpan**

Setelah aturan dibuat, hubungkan ke **Model** yang sesuai di grup **Referensi**.

<a name="mengubah-data"></a>
## Mengubah Data

1. Pada daftar penyusutan, klik **Ubah** pada baris yang ingin diedit (atau **Lihat** lalu **Ubah**)
2. Perbarui field yang diperlukan
3. Klik **Simpan**

> {note} Dampak perubahan
>
> Mengubah aturan penyusutan dapat memengaruhi perhitungan nilai buku barang yang terhubung ke model terkait.

<a name="menghapus-data"></a>
## Menghapus Data

**Dari halaman ubah:**

1. Buka aturan penyusutan → klik **Ubah**
2. Klik **Hapus** di bagian atas halaman
3. Konfirmasi penghapusan

**Dari daftar (beberapa sekaligus):**

1. Centang satu atau lebih baris di tabel
2. Pilih aksi massal **Hapus**
3. Konfirmasi penghapusan

> {warning} Data terkait
>
> Aturan penyusutan yang masih dipakai oleh **Model** sebaiknya tidak dihapus. Periksa kolom **Jumlah Model** di tabel sebelum menghapus.

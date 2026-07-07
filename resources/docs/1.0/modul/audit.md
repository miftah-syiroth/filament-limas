# Audit Barang

Modul **Audit barang** mencatat hasil inspeksi fisik terhadap aset — kondisi barang, kecocokan lokasi, dan jadwal audit berikutnya. Setiap audit terhubung langsung ke satu [Barang](/{{route}}/{{version}}/modul/item).

---

- [Cara Membuka](#cara-membuka)
- [Konsep Audit](#konsep-audit)
- [Membuat Audit](#membuat-audit)
- [Melihat Audit](#melihat-audit)
- [Mengubah Audit](#mengubah-audit)
- [Menghapus Audit](#menghapus-audit)
- [Kondisi dan Hasil Audit](#kondisi-dan-hasil-audit)
- [Hubungan dengan Modul Lain](#hubungan-dengan-modul-lain)
- [Langkah Selanjutnya](#langkah-selanjutnya)

<a name="cara-membuka"></a>
## Cara Membuka

**Untuk mencatat audit baru:**

1. Buka detail [Barang](/{{route}}/{{version}}/modul/item)
2. Klik tab **Audit**

**Untuk melihat riwayat audit semua barang:**

- Sidebar → grup **Laporan** → **Audit barang** (hanya lihat, tanpa tombol tambah)

<a name="konsep-audit"></a>
## Konsep Audit

Audit adalah pencatatan inspeksi fisik berkala. Setiap kali audit dibuat:

- Tanggal **Audit terakhir** dan **Audit berikutnya** pada barang diperbarui otomatis
- Jadwal audit berikutnya dihitung dari interval audit pada model barang
- Opsional: status barang dapat diubah sekaligus (misalnya Aktif → Rusak)

Hasil audit **Perlu pemeliharaan** menjadi dasar untuk membuat tiket di modul [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance).

Barang dengan **audit berikutnya** yang sudah lewat atau dalam 7 hari ke depan muncul sebagai peringatan di dasbor.

<a name="membuat-audit"></a>
## Membuat Audit

1. Buka detail barang → tab **Audit**
2. Klik **Tambah audit**
3. Isi form:
   - **Tanggal audit** (wajib)
   - **Kondisi** (wajib) — kondisi fisik barang saat inspeksi
   - **Hasil** (wajib) — tindak lanjut yang disarankan
   - **Lokasi diverifikasi** — centang jika barang berada di lokasi yang tercatat
   - **Tanggal audit berikutnya** (wajib) — terisi otomatis berdasarkan interval model, dapat disesuaikan
   - **Catatan** (opsional)
   - **Status ke** (opsional) — ubah status barang jika diperlukan
4. Klik **Simpan**

<a name="melihat-audit"></a>
## Melihat Audit

**Dari tab Audit pada barang:**

- Klik ikon mata pada baris audit untuk melihat detail dalam modal

**Dari menu Audit barang (Laporan):**

- Daftar semua audit lintas barang
- Klik baris untuk melihat detail audit

Informasi yang ditampilkan: kode audit, barang, tanggal audit, audit berikutnya, kondisi, hasil, lokasi diverifikasi, catatan, dan status barang saat itu.

<a name="mengubah-audit"></a>
## Mengubah Audit

Audit yang sudah tercatat **tidak dapat diubah**. Ini menjaga integritas jejak inspeksi.

Jika ada kesalahan input, hapus audit tersebut (lihat bagian berikutnya) lalu buat audit baru dengan data yang benar — dengan memperhatikan dampaknya pada jadwal audit barang.

<a name="menghapus-audit"></a>
## Menghapus Audit

Penghapusan audit dilakukan dari tab **Audit** pada detail barang:

1. Centang satu atau lebih baris audit
2. Pilih aksi massal **Hapus**
3. Konfirmasi

Audit yang dihapus menggunakan soft delete dan dapat dipulihkan lewat filter **Terhapus**, selama Anda memiliki izin pemulihan.

> {warning} Dampak penghapusan
>
> Menghapus audit dapat memengaruhi jejak inspeksi barang. Pastikan penghapusan sudah disetujui sesuai kebijakan institusi.

<a name="kondisi-dan-hasil-audit"></a>
## Kondisi dan Hasil Audit

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
| Perlu pemeliharaan | Buat tiket di [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance) |
| Perlu penggantian | Pertimbangkan pengadaan baru |
| Buang | Ajukan pemusnahan |

<a name="hubungan-dengan-modul-lain"></a>
## Hubungan dengan Modul Lain

| Modul | Hubungan |
|-------|----------|
| [Barang](/{{route}}/{{version}}/modul/item) | Setiap audit milik satu barang; memperbarui jadwal audit pada barang |
| [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance) | Tiket pemeliharaan dapat dikaitkan ke audit sumber (`Kode audit`) |
| [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status) | Perubahan status opsional saat audit tercatat sebagai jejak transfer |

<a name="langkah-selanjutnya"></a>
## Langkah Selanjutnya

- [Pemeliharaan](/{{route}}/{{version}}/modul/maintenance)
- [Barang](/{{route}}/{{version}}/modul/item)
- [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status)

# Pemeliharaan

Modul **Pemeliharaan** mengelola tiket perawatan dan perbaikan aset — dari laporan masuk hingga selesai. Setiap tiket terhubung ke satu [Barang](/{{route}}/{{version}}/modul/item) dan dapat dikaitkan ke hasil [Audit Barang](/{{route}}/{{version}}/modul/audit).

---

- [Cara Membuka](#cara-membuka)
- [Konsep Pemeliharaan](#konsep-pemeliharaan)
- [Membuat Tiket Pemeliharaan](#membuat-tiket-pemeliharaan)
- [Melihat Tiket Pemeliharaan](#melihat-tiket-pemeliharaan)
- [Mengubah Tiket Pemeliharaan](#mengubah-tiket-pemeliharaan)
- [Menghapus Tiket Pemeliharaan](#menghapus-tiket-pemeliharaan)
- [Tipe dan Status](#tipe-dan-status)
- [Hubungan dengan Modul Lain](#hubungan-dengan-modul-lain)
- [Langkah Selanjutnya](#langkah-selanjutnya)

<a name="cara-membuka"></a>
## Cara Membuka

**Untuk membuat dan mengelola tiket:**

1. Buka detail [Barang](/{{route}}/{{version}}/modul/item)
2. Klik tab **Pemeliharaan**

**Untuk melihat riwayat pemeliharaan semua barang:**

- Sidebar → grup **Laporan** → **Pemeliharaan** (hanya lihat, tanpa tombol tambah)

<a name="konsep-pemeliharaan"></a>
## Konsep Pemeliharaan

Tiket pemeliharaan melacak siklus perawatan aset:

1. **Dilaporkan** — keluhan atau rencana perawatan masuk
2. **Sedang berjalan** — perbaikan/perawatan sedang dikerjakan
3. **Selesai** — pekerjaan rampung, biaya tercatat
4. **Dibatalkan** — tidak jadi dilakukan

Saat membuat atau mengubah tiket, Anda dapat sekaligus mengubah **status barang** (misalnya Aktif → Sedang diperbaiki → Aktif). Perubahan status tercatat di [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status).

Tiket berstatus **Dilaporkan** atau **Sedang berjalan** muncul sebagai peringatan di dasbor.

<a name="membuat-tiket-pemeliharaan"></a>
## Membuat Tiket Pemeliharaan

1. Buka detail barang → tab **Pemeliharaan**
2. Klik **Tambah pemeliharaan**
3. Isi form:
   - **Tipe** (wajib) — Preventif, Perbaikan, Upgrade, atau Inspeksi
   - **Tanggal laporan** (wajib)
   - **Catatan** — keluhan atau rencana perawatan
   - **Audit** (opsional) — kaitkan ke audit sumber jika tiket berasal dari hasil inspeksi
   - **Status ke** (opsional) — ubah status barang, misalnya ke **Sedang diperbaiki**
4. Klik **Simpan** — status awal tiket: **Dilaporkan**

<a name="melihat-tiket-pemeliharaan"></a>
## Melihat Tiket Pemeliharaan

**Dari tab Pemeliharaan pada barang:**

- Tabel menampilkan semua tiket barang tersebut
- Klik ikon mata untuk melihat detail dalam modal

**Dari menu Pemeliharaan (Laporan):**

- Daftar semua tiket lintas barang
- Klik baris untuk melihat detail

Informasi yang ditampilkan: barang, tipe, tanggal laporan/mulai/selesai, biaya, status, kode audit terkait, dan catatan.

<a name="mengubah-tiket-pemeliharaan"></a>
## Mengubah Tiket Pemeliharaan

1. Buka detail barang → tab **Pemeliharaan**
2. Klik ikon ubah pada baris tiket
3. Perbarui sesuai progres:
   - Ubah **Status** ke **Sedang berjalan** — isi **Tanggal mulai**
   - Ubah **Status** ke **Selesai** — isi **Tanggal mulai**, **Tanggal selesai** (harus setelah atau sama dengan tanggal mulai), dan **Biaya** jika ada
   - Ubah **Status** ke **Dibatalkan** — jika pekerjaan tidak jadi dilakukan
4. Jika perlu, ubah **Status ke** pada bagian status barang
5. Klik **Simpan**

<a name="menghapus-tiket-pemeliharaan"></a>
## Menghapus Tiket Pemeliharaan

Penghapusan dapat dilakukan per baris atau massal dari tab **Pemeliharaan** pada detail barang:

1. Klik ikon hapus pada baris tiket, atau centang beberapa baris lalu pilih aksi massal **Hapus**
2. Konfirmasi

Tiket yang dihapus menggunakan soft delete dan dapat dipulihkan lewat filter **Terhapus**.

> {warning} Dampak pada barang
>
> Menghapus tiket pemeliharaan dapat memblokir penghapusan barang jika tiket tersebut masih tercatat. Barang dengan riwayat pemeliharaan tidak dapat dihapus hingga tiket terkait dibersihkan.

<a name="tipe-dan-status"></a>
## Tipe dan Status

**Tipe pemeliharaan:**

| Tipe | Kegunaan |
|------|----------|
| Preventif | Perawatan terjadwal |
| Perbaikan | Memperbaiki kerusakan |
| Upgrade | Peningkatan spesifikasi |
| Inspeksi | Pemeriksaan rutin |

**Status tiket:**

| Status | Arti |
|--------|------|
| Dilaporkan | Baru masuk, belum dikerjakan |
| Sedang berjalan | Perawatan/perbaikan sedang berlangsung |
| Selesai | Pekerjaan rampung |
| Dibatalkan | Tidak jadi dilakukan |

<a name="hubungan-dengan-modul-lain"></a>
## Hubungan dengan Modul Lain

| Modul | Hubungan |
|-------|----------|
| [Barang](/{{route}}/{{version}}/modul/item) | Setiap tiket milik satu barang |
| [Audit Barang](/{{route}}/{{version}}/modul/audit) | Tiket dapat dikaitkan ke audit yang merekomendasikan pemeliharaan |
| [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status) | Perubahan status barang dari tiket tercatat sebagai jejak status |

<a name="langkah-selanjutnya"></a>
## Langkah Selanjutnya

- [Audit Barang](/{{route}}/{{version}}/modul/audit)
- [Barang](/{{route}}/{{version}}/modul/item)
- [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status)

# Pergerakan Stok

**Pergerakan stok** digunakan untuk barang **habis pakai (consumable)** dan barang stok massal lainnya — mencatat stok masuk, keluar, dan penyesuaian secara permanen. Setiap pergerakan terhubung ke satu [Barang](/{{route}}/{{version}}/modul/item).

---

- [Cara Membuka](#cara-membuka)
- [Konsep Pergerakan Stok](#konsep-pergerakan-stok)
- [Membuat Pergerakan Stok](#membuat-pergerakan-stok)
- [Melihat Riwayat Stok](#melihat-riwayat-stok)
- [Mengubah Pergerakan Stok](#mengubah-pergerakan-stok)
- [Menghapus Pergerakan Stok](#menghapus-pergerakan-stok)
- [Hubungan dengan Modul Lain](#hubungan-dengan-modul-lain)
- [Langkah Selanjutnya](#langkah-selanjutnya)

<a name="cara-membuka"></a>
## Cara Membuka

1. Buka detail [Barang](/{{route}}/{{version}}/modul/item) kategori habis pakai atau barang stok massal
2. Klik tab **Stok**

> {note} Tab Stok tidak tersedia
>
> Tab **Stok** hanya muncul pada barang **stok massal** (pelacakan individu nonaktif). Barang dengan pelacakan individu tidak memiliki tab ini — setiap unit sudah tercatat sebagai barang terpisah.

<a name="konsep-pergerakan-stok"></a>
## Konsep Pergerakan Stok

Pergerakan stok mengubah **jumlah** stok barang, bukan memindahkan aset ke lokasi lain.

| Situasi | Modul yang digunakan |
|---------|---------------------|
| Stok ATK masuk dari pengadaan baru | Pergerakan stok — tipe **In** |
| Stok keluar karena pemakaian harian | Pergerakan stok — tipe **Out** |
| Koreksi jumlah setelah opname | Pergerakan stok — tipe **Adjustment** |
| Meminjamkan proyektor untuk acara 3 hari | [Peminjaman](/{{route}}/{{version}}/modul/peminjaman) |
| Memindahkan laptop ke departemen lain permanen | [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status) |

**Kuantitas barang** dihitung otomatis dari jumlah semua pergerakan stok. Saat membuat barang stok massal, sistem mencatat stok awal secara otomatis.

Sistem mencegah stok menjadi negatif. Jika stok consumable di bawah batas minimum yang ditetapkan pada model, peringatan muncul di dasbor.

<a name="membuat-pergerakan-stok"></a>
## Membuat Pergerakan Stok

1. Buka barang stok massal → tab **Stok**
2. Klik **Tambah stok**
3. Pilih **Tipe**:
   - **In** — stok masuk; kuantitas harus **positif**
   - **Out** — stok keluar; kuantitas harus **negatif**
   - **Adjustment** — koreksi stok; kuantitas positif atau negatif sesuai kebutuhan
4. Isi **Kuantitas** (tidak boleh 0)
5. Isi **Catatan** (opsional) — misalnya nomor PO, alasan pemakaian
6. Klik **Simpan**

> {warning} Stok tidak boleh negatif
>
> Jika pergerakan menyebabkan saldo stok di bawah nol, sistem menolak penyimpanan dan menampilkan pesan berisi stok saat ini.

<a name="melihat-riwayat-stok"></a>
## Melihat Riwayat Stok

Tabel di tab **Stok** menampilkan semua pergerakan: tipe, kuantitas, catatan, dan tanggal dibuat. Saldo stok terkini tercermin pada field **Kuantitas** di detail barang.

<a name="mengubah-pergerakan-stok"></a>
## Mengubah Pergerakan Stok

Pergerakan stok yang sudah tercatat **tidak dapat diubah**. Ini menjaga integritas jejak stok.

Jika ada kesalahan, hapus pergerakan yang salah lalu buat pergerakan baru dengan data benar. Kuantitas barang akan dihitung ulang otomatis.

<a name="menghapus-pergerakan-stok"></a>
## Menghapus Pergerakan Stok

1. Buka tab **Stok** pada detail barang
2. Centang satu atau lebih baris pergerakan
3. Pilih aksi massal **Hapus**
4. Konfirmasi

Setelah penghapusan, kuantitas barang dihitung ulang dari sisa pergerakan. Pergerakan yang dihapus dapat dipulihkan lewat filter **Terhapus**.

> {warning} Dampak pada barang
>
> Barang dengan saldo stok lebih dari 0 tidak dapat dihapus. Kosongkan atau sesuaikan stok terlebih dahulu jika penghapusan barang diperlukan.

<a name="hubungan-dengan-modul-lain"></a>
## Hubungan dengan Modul Lain

| Modul | Hubungan |
|-------|----------|
| [Barang](/{{route}}/{{version}}/modul/item) | Setiap pergerakan milik satu barang; memperbarui kuantitas barang |
| [Peminjaman](/{{route}}/{{version}}/modul/peminjaman) | Untuk pemindahan sementara, bukan perubahan stok |
| [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status) | Untuk perpindahan permanen lokasi, bukan perubahan stok |

<a name="langkah-selanjutnya"></a>
## Langkah Selanjutnya

- [Barang](/{{route}}/{{version}}/modul/item)
- [Transfer & Status](/{{route}}/{{version}}/modul/riwayat-status)
- [Peminjaman](/{{route}}/{{version}}/modul/peminjaman)

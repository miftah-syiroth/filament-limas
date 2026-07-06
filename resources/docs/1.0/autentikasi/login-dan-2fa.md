# Login & Autentikasi Dua Faktor

Panduan ini menjelaskan cara masuk ke SIRIS dan mengelola autentikasi dua faktor (2FA) dari sudut pandang pengguna.

## Cara Login

1. Buka halaman login di `/login`
2. Masukkan **email** dan **password** akun Anda
3. Centang **Ingat saya** jika menggunakan perangkat pribadi yang aman
4. Klik tombol login

Setelah berhasil, Anda diarahkan ke panel admin di `/admin`.

> {note} Syarat login
>
> Akun Anda harus sudah terdaftar dan memiliki **minimal satu peran** (admin, operator, atau super admin). Tanpa peran, login akan ditolak meskipun email dan password benar.

## Login via SSO

Jika tombol **My UHB** (atau nama provider SSO lain) tampil di halaman login, Anda dapat masuk tanpa password. Lihat [SSO My UHB](/{{route}}/{{version}}/autentikasi/sso).

## Autentikasi Dua Faktor (2FA)

2FA menambah lapisan keamanan dengan kode dari aplikasi authenticator di ponsel Anda.

### Mengaktifkan 2FA

1. Login ke panel admin
2. Buka **Pengaturan profil** (ikon akun di pojok kanan atas)
3. Pilih menu pengaturan **Autentikasi dua faktor**
4. Ikuti petunjuk untuk memindai QR code dengan aplikasi authenticator (Google Authenticator, Authy, dll.)
5. Masukkan kode verifikasi untuk mengonfirmasi
6. **Simpan kode pemulihan** di tempat aman — dipakai jika ponsel tidak tersedia

### Login dengan 2FA Aktif

1. Masukkan email dan password seperti biasa
2. Anda diarahkan ke halaman verifikasi 2FA
3. Masukkan kode 6 digit dari aplikasi authenticator, **atau** gunakan salah satu kode pemulihan

### Menonaktifkan 2FA

Buka pengaturan profil → Autentikasi dua faktor → nonaktifkan. Anda mungkin diminta memasukkan password untuk konfirmasi.

## Logout

Klik menu akun di pojok kanan atas panel admin, lalu pilih **Keluar**.

## Kesalahan Umum

| Gejala | Kemungkinan penyebab | Solusi |
|--------|---------------------|--------|
| Login gagal meski password benar | Akun belum punya peran | Hubungi admin untuk assign peran |
| Tidak bisa akses panel admin | Peran tidak memiliki izin panel | Hubungi admin |
| Kode 2FA selalu ditolak | Jam perangkat tidak akurat | Sinkronkan waktu ponsel/komputer |
| Lupa password | — | Gunakan tautan lupa password di halaman login, atau hubungi admin |

## Langkah Selanjutnya

- [SSO My UHB](/{{route}}/{{version}}/autentikasi/sso)
- [Navigasi panel](/{{route}}/{{version}}/panel-admin/navigasi)

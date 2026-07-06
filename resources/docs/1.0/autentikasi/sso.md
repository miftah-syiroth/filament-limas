# SSO My UHB

Login tunggal (SSO) memungkinkan Anda masuk ke SIRIS menggunakan akun **My UHB** tanpa mengetik password SIRIS.

## Cara Login via SSO

1. Buka halaman login di `/login`
2. Klik tombol **My UHB** (atau label provider SSO yang ditampilkan)
3. Anda diarahkan ke halaman login My UHB
4. Masukkan kredensial My UHB Anda
5. Setelah berhasil, Anda kembali ke SIRIS dan langsung masuk ke panel admin

> {warning} Tidak ada pendaftaran otomatis
>
> SSO **tidak** membuat akun baru di SIRIS. Email My UHB Anda harus **sudah terdaftar** sebagai pengguna SIRIS dan memiliki peran yang sesuai. Jika belum, hubungi administrator.

## Kapan Tombol SSO Muncul?

Tombol SSO hanya tampil jika layanan My UHB sudah diaktifkan di institusi Anda. Jika tombol tidak ada, gunakan login email dan password biasa.

## Pesan Error yang Mungkin Muncul

| Pesan / kondisi | Arti | Tindakan |
|-----------------|------|----------|
| Akses ditolak / 403 | Email tidak terdaftar atau tidak punya peran | Hubungi admin untuk mendaftarkan akun |
| Login OAuth ditolak | Anda membatalkan login di My UHB | Coba lagi dan selesaikan login |
| Sesi tidak valid | Sesi login kedaluwarsa | Klik tombol SSO lagi dari awal |
| Email tidak ditemukan | Profil My UHB tidak memiliki email | Hubungi admin IT |
| Login gagal | Gangguan sementara | Coba lagi beberapa saat, atau gunakan login password |

## Keamanan

- Setiap login SSO berhasil membuat sesi baru di browser Anda
- Logout dari SIRIS tidak otomatis logout dari My UHB
- Gunakan perangkat dan jaringan yang Anda percayai

## Langkah Selanjutnya

- [Login & 2FA](/{{route}}/{{version}}/autentikasi/login-dan-2fa)
- [Navigasi panel](/{{route}}/{{version}}/panel-admin/navigasi)

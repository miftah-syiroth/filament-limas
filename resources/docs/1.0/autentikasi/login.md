# Login

Panduan ini menjelaskan cara masuk ke panel admin SIRIS. Anda dapat login dengan **email dan password**, atau melalui **SSO My UHB** jika tombolnya tersedia di halaman login.

---

- [Login Email dan Password](#login-email-dan-password)
- [Login dengan My UHB](#login-dengan-my-uhb)
- [Logout](#logout)
- [Kesalahan Umum](#kesalahan-umum)

<a name="login-email-dan-password"></a>
## Login Email dan Password

1. Buka halaman login di `/login`
2. Masukkan **email** dan **password** akun Anda
3. Centang **Ingat saya** jika menggunakan perangkat pribadi yang aman (opsional)
4. Klik tombol login

Setelah berhasil, Anda diarahkan ke panel admin di `/admin`.

> {info} Syarat login
>
> Akun Anda harus sudah terdaftar dan memiliki **minimal satu peran** (admin, operator, atau super admin). Tanpa peran, login akan ditolak meskipun email dan password benar.

<a name="login-dengan-my-uhb"></a>
## Login dengan My UHB

1. Pastikan Anda **sudah login** di [https://my.uhb.ac.id](https://my.uhb.ac.id) **di browser yang sama** — SSO memakai sesi My UHB yang aktif
2. Buka halaman login di `/login`
3. Klik tombol **Masuk dengan My UHB**
4. Setelah berhasil, Anda kembali ke SIRIS dan masuk ke panel admin di `/admin`

> {warning} Tidak ada pendaftaran otomatis
>
> SSO **tidak** membuat akun baru di SIRIS. Email My UHB Anda harus **sudah terdaftar** sebagai pengguna SIRIS dan memiliki peran yang sesuai. Jika belum, hubungi administrator atau tim IT.

Tombol **Masuk dengan My UHB** hanya tampil jika integrasi My UHB aktif di lingkungan Anda. Jika tombol tidak ada, gunakan login email dan password.

<a name="logout"></a>
## Logout

Klik menu akun di pojok kanan atas panel admin, lalu pilih **Keluar**.  
Logout dari SIRIS **tidak otomatis logout** dari My UHB.

<a name="kesalahan-umum"></a>
## Kesalahan Umum

| Gejala | Kemungkinan penyebab | Solusi |
|--------|---------------------|--------|
| Login gagal meski password benar | Akun belum punya peran | Hubungi admin untuk assign peran |
| Tidak bisa akses panel admin | Peran tidak memiliki izin panel | Hubungi admin |
| Lupa password | — | Gunakan tautan lupa password di halaman login, atau hubungi admin |

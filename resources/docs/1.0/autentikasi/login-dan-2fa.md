# Login dan Autentikasi Dua Faktor

## Halaman Login

URL: `/login`

Halaman login menggunakan komponen Livewire Flux (`resources/views/pages/auth/login.blade.php`) dengan:

- Form email dan password
- Opsi "Ingat saya"
- Tombol SSO (jika OAuth dikonfigurasi)
- Link ke pengaturan 2FA setelah login

## Proses Login Password

Logika autentikasi kustom di `FortifyServiceProvider`:

1. Cari user berdasarkan email **yang memiliki role**
2. Verifikasi password dengan `Hash::check`
3. Jika gagal, kembalikan `null` → Fortify menolak login

```php
User::query()
    ->where('email', $email)
    ->whereHas('roles')
    ->first();
```

Session `auth_login_method` diset ke `password` (default) saat login berhasil.

## Rate Limiting

| Aksi | Batas |
|------|-------|
| Login | 5 percobaan per menit per email+IP |
| Two-factor challenge | 5 percobaan per menit per session login ID |

## Autentikasi Dua Faktor (2FA)

2FA diaktifkan melalui Fortify dengan fitur:

- `confirm` — user harus mengonfirmasi 2FA setelah setup
- `confirmPassword` — memerlukan konfirmasi password sebelum mengelola 2FA

### Mengelola 2FA

Setelah login, buka `/settings/two-factor` untuk:

- Mengaktifkan aplikasi authenticator (TOTP)
- Memindai QR code
- Menyimpan recovery codes

### Challenge 2FA

Jika 2FA aktif, setelah password benar pengguna diarahkan ke `/two-factor-challenge` untuk memasukkan kode TOTP atau recovery code.

## Logout

Logout dilakukan via Fortify (`POST /logout`). Session diinvalidasi dan token CSRF di-regenerate.

## Kesalahan Umum

| Gejala | Penyebab | Solusi |
|--------|----------|--------|
| Login gagal meski password benar | User tidak punya role | Assign role via UserResource |
| Tidak bisa akses `/admin` | Role bukan admin/operator | Periksa `User::canAccessPanel()` |
| 2FA challenge gagal | Waktu perangkat tidak sinkron | Sinkronkan jam perangkat |

## Langkah Selanjutnya

- [SSO My UHB](/{{route}}/{{version}}/autentikasi/sso) — login tanpa password
- [Peran dan Izin](/{{route}}/{{version}}/administrasi/peran-izin)

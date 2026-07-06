# Gambaran Umum Autentikasi

SIRIS menggunakan beberapa lapisan autentikasi yang bekerja bersama untuk mengamankan akses ke panel admin.

## Komponen

| Komponen | Peran |
|----------|-------|
| **Laravel Fortify** | Login password, 2FA, konfirmasi password |
| **Laravel Socialite** | SSO OAuth2 via My UHB |
| **Filament Authenticate** | Middleware panel admin `/admin` |
| **Filament Shield** | Otorisasi per resource berdasarkan peran |

## Alur Umum

```mermaid
flowchart TD
    Guest[Pengguna belum login] --> LoginPage[/login]
    LoginPage --> Method{Metode login}
    Method -->|Password| Fortify[Fortify authenticateUsing]
    Method -->|SSO| OAuth[/oauth]
    Fortify --> RoleCheck{Punya role?}
    OAuth --> SsoCallback[/oauth/callback]
    SsoCallback --> AuthAction[AuthenticateOAuthUser]
    AuthAction --> RoleCheck
    RoleCheck -->|Tidak| Fail[Gagal / 403]
    RoleCheck -->|Ya| Session[Regenerasi session]
    Session --> Admin[/admin]
    Admin --> PanelCheck{canAccessPanel?}
    PanelCheck -->|admin/super_admin/operator| Granted[Akses diberikan]
    PanelCheck -->|Tidak| Denied[403 Filament]
```

## Syarat Login

Baik login password maupun SSO mewajibkan:

1. Email terdaftar di tabel `users`
2. User memiliki **minimal satu role** (`whereHas('roles')`)

Pengguna tanpa role tidak dapat masuk, meskipun email dan password benar.

## Redirect Setelah Login

- Default Fortify home: `/admin` (`config/fortify.php`)
- SSO callback: `redirect()->intended('/admin')`
- Middleware guest: `/` mengarahkan ke route `login`

## Fitur Fortify yang Aktif

| Fitur | Status |
|-------|--------|
| Login | Aktif |
| Two-factor authentication | Aktif (dengan konfirmasi password) |
| Registration | Nonaktif |
| Password reset | Nonaktif |
| Email verification | Nonaktif |

## Pengaturan Pengguna

Pengguna yang sudah login dapat mengakses:

| Route | Keterangan |
|-------|------------|
| `/settings/profile` | Edit profil |
| `/settings/password` | Ubah password (perlu verified) |
| `/settings/appearance` | Tampilan |
| `/settings/two-factor` | Kelola 2FA |

## Aktivitas Auth

Listener `LogAuthenticationActivity` mencatat event login/logout ke Spatie Activity Log (log `auth`) dengan metode login (`password` atau `sso`).

## Halaman Terkait

- [Login dan 2FA](/{{route}}/{{version}}/autentikasi/login-dan-2fa)
- [SSO My UHB](/{{route}}/{{version}}/autentikasi/sso)
- [Peran dan Izin](/{{route}}/{{version}}/administrasi/peran-izin)

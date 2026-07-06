# Gambaran Umum Panel Admin

Panel admin SIRIS adalah antarmuka utama untuk seluruh operasi inventori, diakses di `/admin`.

## Konfigurasi

Panel dikonfigurasi di `app/Providers/Filament/AdminPanelProvider.php`:

| Pengaturan | Nilai |
|------------|-------|
| ID panel | `admin` |
| Path | `/admin` |
| Panel default | Ya |
| Warna utama | Amber |
| Mode SPA | Aktif |
| Lebar konten | Full width |
| Sidebar | Dapat di-collapse |
| Global search | Opt-in, debounce 750ms |
| Notifikasi | Database notifications |
| Favicon | `logo.webp` |

## Discovery Otomatis

Filament menemukan resource, halaman, dan widget secara otomatis:

```
app/Filament/Resources/   → 18 resources
app/Filament/Pages/       → Dashboard kustom, DepreciationItemsPage, EditProfile
app/Filament/Widgets/     → 20+ widget dashboard
```

## Plugin

### Filament Shield

Plugin `FilamentShieldPlugin` mengelola RBAC:

- Grup navigasi: **Administration**
- Generate permissions per resource/action
- Integrasi dengan Spatie Permission

## Halaman Kustom

| Halaman | Path | Keterangan |
|---------|------|------------|
| `Dashboard` | `/admin` | Dashboard dengan widget KPI dan grafik |
| `DepreciationItemsPage` | `/admin/depreciation-items` | Laporan item depresiasi + ekspor |
| `EditProfile` | `/admin/profile` | Profil — hanya ubah password (nama/email disabled) |

## Middleware

Rangkaian middleware standar Laravel + Filament:

- Session, CSRF, cookie encryption
- `Authenticate` — wajib login
- `AuthenticateSession` — validasi session

## Global Search

Hanya resource yang opt-in yang muncul di pencarian global. Saat ini `ItemResource` mengindeks:

- Nomor seri
- Nama model
- Lokasi, departemen, ruang
- Penanggung jawab

## Bahasa

Paket `filament-language-switch` memungkinkan pengguna mengganti bahasa antarmuka (ID/EN) dari panel.

## Langkah Selanjutnya

- [Navigasi](/{{route}}/{{version}}/panel-admin/navigasi) — struktur menu sidebar
- [Dashboard](/{{route}}/{{version}}/panel-admin/dashboard) — widget dan KPI

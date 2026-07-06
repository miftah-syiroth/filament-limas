# Peran dan Izin

SIRIS menggunakan **Filament Shield** di atas **Spatie Laravel Permission** untuk kontrol akses berbasis peran (RBAC).

## Peran Bawaan

| Peran | Keterangan |
|-------|------------|
| `super_admin` | Akses penuh — bypass semua gate Shield |
| `admin` | Administrator — semua permissions di-sync via RoleSeeder |
| `operator` | Operator harian — permissions terbatas |

Enum: `App\Enums\RoleName`

## Resource

**RoleResource** (extends Shield `RoleResource`) mengelola peran dan permissions di grup **Administration**.

### Peran Sistem

Peran bawaan (`super_admin`, `admin`, `operator`) **tidak dapat diubah** pada halaman edit — hanya peran kustom yang dapat dimodifikasi.

## Generate Permissions

```bash
php artisan shield:generate --panel=admin --all --option=policies_and_permissions --ignore-existing-policies
```

Atau via Makefile:

```bash
make shield-generate
```

Perintah ini:

1. Membuat permission per resource/action Filament
2. Membuat policy di `app/Policies/`
3. Mendaftarkan permission ke database

## Sync ke Role Admin

```bash
php artisan db:seed --class=RoleSeeder
```

RoleSeeder memberikan **semua permissions** ke role `admin`.

## Super Admin Bypass

Konfigurasi `config/filament-shield.php` mengaktifkan intercept gate untuk `super_admin` — role ini melewati semua pengecekan permission.

## Policies

Policy otomatis di-generate per model, contoh:

- `ItemPolicy` — view, create, update, delete item
- `UserPolicy` — dengan guard tambahan untuk role bawaan
- `RolePolicy` — proteksi role sistem

## Halaman Kustom

Halaman seperti `DepreciationItemsPage` menggunakan trait `HasPageShield` untuk permission akses halaman.

## Matriks Akses Umum

| Area | super_admin | admin | operator |
|------|:-----------:|:-----:|:--------:|
| Inventori CRUD | ✓ | ✓ | Sesuai permission |
| Laporan (read) | ✓ | ✓ | Sesuai permission |
| User management | ✓ | ✓ | ✗ |
| Role management | ✓ | Terbatas | ✗ |

> {note} Operator
>
> Permission operator dapat disesuaikan per kebutuhan institusi melalui RoleResource.

## Langkah Selanjutnya

- [Referensi Enum](/{{route}}/{{version}}/referensi/enum)
- [Testing](/{{route}}/{{version}}/pengembangan/testing)

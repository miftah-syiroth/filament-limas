# Pengguna

Manajemen akun pengguna sistem.

**Resource:** `UserResource` · **Model:** `App\Models\User`

## Halaman

| Halaman | Keterangan |
|---------|------------|
| List | Daftar pengguna |
| Create | Tambah pengguna baru |
| View | Detail pengguna |
| Edit | Ubah data dan role |

## Data Pengguna

| Field | Keterangan |
|-------|------------|
| `name` | Nama lengkap |
| `email` | Email unik (digunakan login & SSO) |
| `password` | Password (di-hash) |
| `oauth` | Atribut SSO schemaless (Spatie) |

## Role

Setiap pengguna harus memiliki **minimal satu role** agar dapat login. Role diassign melalui form Edit User (relasi Spatie Permission).

## Two-Factor Authentication

Pengguna dapat mengaktifkan 2FA melalui `/settings/two-factor` setelah login. Status 2FA dikelola oleh Laravel Fortify.

## Akses Panel

Method `User::canAccessPanel()` mengizinkan akses panel admin untuk role:

- `super_admin`
- `admin`
- `operator`

## Profil di Panel

Halaman profil Filament (`EditProfile`) hanya mengizinkan perubahan **password**. Nama dan email tidak dapat diubah dari panel.

## Activity Log

Perubahan data pengguna tercatat via Spatie Activity Log (`LogsActivity` trait).

## Membuat Pengguna Baru

1. Buka **Users** → Create
2. Isi nama, email, password
3. Assign role (wajib agar user dapat login)
4. Simpan

> {warning} Syarat login
>
> User tanpa role tidak dapat login via password maupun SSO.

## Langkah Selanjutnya

- [Peran dan Izin](/{{route}}/{{version}}/administrasi/peran-izin)

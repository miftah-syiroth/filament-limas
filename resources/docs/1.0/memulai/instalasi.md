# Instalasi

Panduan ini menjelaskan instalasi awal proyek SIRIS di lingkungan pengembangan lokal.

## Prasyarat

- PHP 8.4
- Composer 2.x
- Node.js 20+ dan npm
- PostgreSQL 15+
- Redis

## Instalasi Cepat

Proyek menyediakan target Makefile untuk instalasi otomatis:

```bash
# Salin .env, install dependensi, migrate, seed, generate shield
make install
```

Atau dengan data dummy:

```bash
make install-dummy
```

Target `install` menjalankan urutan berikut:

1. `make env` — salin `.env.example` ke `.env` jika belum ada
2. `make fresh` — `php artisan migrate:fresh`
3. `make seed` — `php artisan db:seed`
4. `make shield-generate` — generate policies dan permissions Filament Shield
5. `make seed-role` — assign permissions ke role admin

## Instalasi Manual

### 1. Dependensi PHP

```bash
composer install
cp .env.example .env   # jika belum ada
php artisan key:generate
```

### 2. Database

Atur koneksi PostgreSQL di `.env`, lalu jalankan migrasi:

```bash
php artisan migrate
php artisan db:seed
```

### 3. Filament Shield

Generate izin resource untuk panel admin:

```bash
php artisan shield:generate --panel=admin --all --option=policies_and_permissions --ignore-existing-policies
php artisan db:seed --class=RoleSeeder
```

### 4. Frontend

```bash
npm install
npm run build
```

### 5. Storage

```bash
php artisan storage:link
```

## Menjalankan Server Pengembangan

```bash
composer run dev
```

Perintah ini menjalankan secara paralel:

- `php artisan serve` — server HTTP
- `php artisan queue:listen` — worker antrian
- `php artisan pail` — log streaming
- `npm run dev` — Vite HMR

Akses aplikasi di `http://127.0.0.1:8000/admin`.

## Dokumentasi

Dokumentasi SIRIS dilayani langsung oleh LaRecipe tanpa langkah build terpisah. Setelah server berjalan, akses di:

```
http://127.0.0.1:8000/docs
```

Sumber markdown berada di `resources/docs/1.0/`. Sidebar navigasi dikonfigurasi di `resources/docs/1.0/index.md`.

## Perintah Berguna

| Perintah | Keterangan |
|----------|------------|
| `make test` | Jalankan test suite |
| `make lint` | Format kode dengan Laravel Pint |
| `make shield-generate` | Regenerasi policies Shield |

## Langkah Selanjutnya

- [Konfigurasi](/{{route}}/{{version}}/memulai/konfigurasi) — sesuaikan variabel lingkungan
- [Autentikasi](/{{route}}/{{version}}/autentikasi/gambaran-umum) — login dan SSO

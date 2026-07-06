# SIRIS — Sistem Informasi Inventori

**SIRIS** (*Sistem Informasi Inventori*) adalah aplikasi manajemen aset dan inventori untuk Universitas Harapan Bangsa. Aplikasi ini memungkinkan tim administrasi melacak aset fisik, peminjaman sementara, audit, perawatan, stok consumable, serta laporan keuangan depresiasi dari satu panel admin terpusat.

## Fitur Utama

- **Inventori aset** — pelacakan per unit dengan nomor seri, status, lokasi, dan penanggung jawab
- **Peminjaman / pemindahan sementara** — memindahkan aset ke lokasi lain dan mengembalikannya
- **Audit & maintenance** — jadwal inspeksi dan tiket perbaikan
- **Stok consumable** — pergerakan stok masuk/keluar untuk barang habis pakai
- **Data master** — organisasi, lokasi, departemen, katalog produk, supplier
- **Dashboard analitik** — KPI, grafik, dan peringatan operasional
- **RBAC** — peran dan izin berbasis Filament Shield
- **SSO** — login tunggal via My UHB OAuth (opsional)

## Stack Teknologi

| Lapisan | Teknologi |
|---------|-----------|
| Backend | Laravel 12, PHP 8.4 |
| Admin UI | Filament v5, Livewire v4 |
| Auth | Laravel Fortify, Socialite (SSO) |
| Database | PostgreSQL (UUID, CITEXT) |
| Cache / Session | Redis |
| Frontend auth | Livewire Flux |
| Dokumentasi | LaRecipe |

## Akses Cepat

| Area | URL |
|------|-----|
| Panel admin | `/admin` |
| Login | `/login` |
| SSO (jika dikonfigurasi) | `/oauth` |
| Dokumentasi ini | `/docs` |

## Navigasi Dokumentasi

- [Memulai](/{{route}}/{{version}}/memulai/pengenalan) — pengenalan, instalasi, konfigurasi
- [Autentikasi](/{{route}}/{{version}}/autentikasi/gambaran-umum) — login, 2FA, SSO
- [Panel Admin](/{{route}}/{{version}}/panel-admin/gambaran-umum) — navigasi dan dashboard
- [Modul](/{{route}}/{{version}}/modul/inventori) — panduan fitur bisnis
- [Referensi Database](/{{route}}/{{version}}/referensi/database) — skema tabel dan relasi

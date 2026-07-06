# Konfigurasi

Variabel lingkungan utama untuk menjalankan SIRIS. Semua nilai diatur di file `.env`.

## Aplikasi

| Variabel | Contoh | Keterangan |
|----------|--------|------------|
| `APP_NAME` | `SIRIS` | Nama aplikasi |
| `APP_URL` | `http://127.0.0.1:8000` | URL dasar aplikasi |
| `APP_LOCALE` | `id` | Bahasa default |
| `APP_DEBUG` | `true` | Mode debug (non-produksi) |

## Database

| Variabel | Contoh | Keterangan |
|----------|--------|------------|
| `DB_CONNECTION` | `pgsql` | Driver database |
| `DB_HOST` | `127.0.0.1` | Host PostgreSQL |
| `DB_PORT` | `5432` | Port |
| `DB_DATABASE` | `limas_filament` | Nama database |
| `DB_USERNAME` | `postgres` | Username |
| `DB_PASSWORD` | `secret` | Password |

## Cache & Session

| Variabel | Contoh | Keterangan |
|----------|--------|------------|
| `CACHE_STORE` | `redis` | Driver cache |
| `SESSION_DRIVER` | `redis` | Driver session |
| `REDIS_HOST` | `127.0.0.1` | Host Redis |
| `REDIS_PORT` | `6379` | Port Redis |

## Media

| Variabel | Contoh | Keterangan |
|----------|--------|------------|
| `MEDIA_DISK` | `public` | Disk penyimpanan media aset |
| `MEDIA_PREFIX` | `/media-library` | Prefix path media |

## SSO / OAuth (My UHB)

Konfigurasi di `config/oauth.php`, dibaca dari variabel berikut:

| Variabel | Keterangan |
|----------|------------|
| `OAUTH_NAME` | Label provider di tombol login |
| `OAUTH_URL` | Base URL server OAuth (mis. `https://my.uhb.ac.id`) |
| `OAUTH_CLIENT_ID` | Client ID aplikasi |
| `OAUTH_CLIENT_SECRET` | Client secret |
| `OAUTH_USER_AGENT` | User-Agent untuk request API profil |

> {note} Tombol SSO
>
> Tombol login SSO hanya muncul jika `OAUTH_CLIENT_ID` terisi dan route `oauth.login` tersedia.

Lihat [SSO](/{{route}}/{{version}}/autentikasi/sso) untuk detail alur autentikasi.

## Mail

Untuk pengembangan, gunakan driver `log`:

```env
MAIL_MAILER=log
```

## Produksi

Pada lingkungan produksi:

- Set `APP_DEBUG=false`
- Gunakan `APP_URL` dengan domain HTTPS yang benar
- Pastikan Redis dan PostgreSQL dapat diakses dari server aplikasi

## Langkah Selanjutnya

- [Gambaran Autentikasi](/{{route}}/{{version}}/autentikasi/gambaran-umum)
- [Panel Admin](/{{route}}/{{version}}/panel-admin/gambaran-umum)

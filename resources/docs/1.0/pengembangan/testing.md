# Testing

SIRIS menggunakan **Pest PHP v4** untuk pengujian otomatis.

## Menjalankan Test

```bash
# Semua test + lint
composer test

# Hanya test
php artisan test --compact

# Filter test tertentu
php artisan test --compact --filter=AuthenticationActivityLogTest
```

## Linting

```bash
make lint
# atau
vendor/bin/pint --dirty --format agent
```

## Struktur Test

```
tests/
├── Feature/          # Feature & integration tests
│   └── Auth/         # Authentication tests
├── Unit/             # Unit tests
└── Pest.php          # Konfigurasi Pest
```

## Testing Filament

Proyek menggunakan `pestphp/pest-plugin-livewire` untuk menguji komponen Livewire/Filament:

```php
use function Pest\Livewire\livewire;

livewire(ListItems::class)
    ->assertCanSeeTableRecords($items)
    ->searchTable($items->first()->serial_number);
```

### Pola Umum

- `actingAs(User::factory()->create())` sebelum test panel
- Create: `->call('create')` + `assertRedirect()`
- Edit: `->call('save')` tanpa `assertRedirect()`
- Actions: `TestAction::make('name')->table($record)`

## Test Autentikasi

| Test | Keterangan |
|------|------------|
| `AuthenticationActivityLogTest` | Log aktivitas login/logout |
| `RegistrationTest` | Registrasi (fitur Fortify nonaktif) |
| SSO tests | Callback OAuth |

## Shield di Test

Setelah menambah resource baru, regenerasi permissions:

```bash
make shield-generate
php artisan db:seed --class=RoleSeeder
```

## Database Test

Test feature menggunakan `RefreshDatabase` trait untuk isolasi data per test. Gunakan factory model:

```php
Item::factory()->create();
User::factory()->create();
```

## CI

GitHub Actions menjalankan test di `.github/workflows/tests.yml` dan lint di `lint.yml`.

## Langkah Selanjutnya

- [Struktur Proyek](/{{route}}/{{version}}/pengembangan/struktur-proyek)
- [Instalasi](/{{route}}/{{version}}/memulai/instalasi)

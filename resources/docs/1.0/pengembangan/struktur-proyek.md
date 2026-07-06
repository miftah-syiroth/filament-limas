# Struktur Proyek

Organisasi kode SIRIS mengikuti struktur Laravel 12 standar dengan konvensi Filament v5.

## Direktori Utama

```
app/
├── Actions/          # Action classes (Fortify, Auth)
├── Enums/            # PHP enums domain
├── Filament/         # UI admin Filament
│   ├── Exports/
│   ├── Imports/
│   ├── Infolists/
│   ├── Pages/
│   ├── Resources/    # 18 resources
│   └── Widgets/      # Dashboard widgets
├── Http/
│   └── Controllers/  # SsoController, dll.
├── Listeners/        # LogAuthenticationActivity
├── Models/           # Eloquent models (UUID PK)
├── Policies/         # Shield-generated policies
├── Providers/        # Service providers + Filament panels
└── Socialite/        # SsoProvider

bootstrap/
├── app.php           # Middleware, routing
└── providers.php     # Provider registration

config/               # Konfigurasi Laravel & paket
database/
├── factories/
├── migrations/
└── seeders/

lang/
├── id/               # Terjemahan Indonesia
└── en/               # Terjemahan Inggris

resources/docs/       # Sumber dokumentasi LaRecipe
└── 1.0/              # Versi dokumentasi aktif

routes/
├── web.php           # SSO routes
├── settings.php      # User settings (Livewire)
└── api.php           # API routes (Passport scaffold)
```

## Pola Filament Resource

Setiap resource diorganisir dalam subfolder:

```
app/Filament/Resources/Items/
├── ItemResource.php
├── Pages/
│   ├── ListItems.php
│   ├── CreateItem.php
│   ├── ViewItem.php
│   ├── EditItem.php
│   └── ManageStockMovements.php  # sub-halaman
├── Schemas/          # Form & infolist schemas
└── Tables/           # Table configuration
```

## Model

- Primary key: **UUID** (`HasUuids` trait)
- Activity log: `LogsActivity` (Spatie) pada model bisnis
- Soft delete: sebagian besar model master dan transaksi
- Media: `Item`, `Model` menggunakan Spatie Media Library

## Provider Penting

| Provider | File |
|----------|------|
| Admin panel | `app/Providers/Filament/AdminPanelProvider.php` |
| App panel | `app/Providers/Filament/AppPanelProvider.php` |
| Fortify | `app/Providers/FortifyServiceProvider.php` |
| App | `app/Providers/AppServiceProvider.php` (SSO driver) |

## Konvensi Kode

- PHP 8.4 dengan return types eksplisit
- Enum keys TitleCase (`ItemStatus::UnderRepair`)
- Form fields: `Filament\Forms\Components\`
- Layout: `Filament\Schemas\Components\`
- Actions: `Filament\Actions\` (bukan sub-namespace Tables/Forms)
- Format kode: Laravel Pint (`make lint`)

## Skills & Panduan AI

File `AGENTS.md` berisi panduan Laravel Boost untuk pengembangan dengan AI assistant.

## Langkah Selanjutnya

- [Testing](/{{route}}/{{version}}/pengembangan/testing)
- [Referensi Database](/{{route}}/{{version}}/referensi/database)

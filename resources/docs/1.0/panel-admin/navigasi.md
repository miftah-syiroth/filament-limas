# Navigasi Panel

Sidebar panel admin diorganisir dalam grup navigasi yang didefinisikan di `App\Enums\NavigationGroup`.

## Grup Navigasi

| Grup | Resource / Halaman |
|------|-------------------|
| *(tanpa grup — atas)* | Items, Borrowings |
| **Reference** | Models, Categories, Manufactures, Suppliers |
| **Reports** | Item Audits, Maintenances, Borrowing Items, Activity Logs |
| **Administration** | Users, Roles (Shield) |
| **Master Data** | Organizations, Locations, Departments, Rooms, Depreciations, Units |

## Resource per Grup

### Inventori (atas)

| Resource | Model | Halaman |
|----------|-------|---------|
| **Items** | `Item` | List, Create, View, Edit + sub-halaman |
| **Borrowings** | `Borrowing` | List, Create, View, Edit |

### Reference

| Resource | Model | Halaman |
|----------|-------|---------|
| **Models** | `Model` | CRUD + View, Import |
| **Categories** | `Category` | CRUD + View, Import |
| **Manufactures** | `Manufacture` | CRUD + View, Import |
| **Suppliers** | `Supplier` | CRUD + View |

### Reports

| Resource | Model | Halaman |
|----------|-------|---------|
| **Item Audits** | `ItemAudit` | Manage (list + view) |
| **Maintenances** | `Maintenance` | Manage + Export |
| **Borrowing Items** | `BorrowingItem` | Manage + Export |
| **Activity Logs** | `ActivityLog` | Manage (read-only) |

### Administration

| Resource | Model | Halaman |
|----------|-------|---------|
| **Users** | `User` | CRUD + View |
| **Roles** | `Role` | CRUD + View (Shield) |

### Master Data

| Resource | Model | Halaman |
|----------|-------|---------|
| **Organizations** | `Organization` | CRUD + View |
| **Locations** | `Location` | CRUD + View |
| **Departments** | `Department` | CRUD + View |
| **Rooms** | `Room` | CRUD + View |
| **Depreciations** | `Depreciation` | CRUD + View |
| **Units** | `Unit` | Manage (inline create/edit) |

## Sub-halaman Item

Dari halaman View/Edit Item, navigasi tab ke:

| Sub-halaman | Keterangan |
|-------------|------------|
| Stock Movements | Pergerakan stok consumable |
| Borrowing History | Riwayat peminjaman item |
| State Logs | Jejak perubahan posisi/status |
| Audits | Audit fisik item |
| Maintenances | Tiket perawatan item |

## Halaman Laporan Kustom

| Halaman | Grup | Keterangan |
|---------|------|------------|
| Depreciation Items | Reports | Tabel nilai buku depresiasi |

## Izin Akses

Visibilitas menu dan aksi dikontrol oleh Filament Shield. Lihat [Peran dan Izin](/{{route}}/{{version}}/administrasi/peran-izin).

## Langkah Selanjutnya

- [Dashboard](/{{route}}/{{version}}/panel-admin/dashboard)
- [Modul Inventori](/{{route}}/{{version}}/modul/inventori)

# Riwayat Status

Jejak permanen perubahan lokasi, penugasan, dan status aset.

**Sub-halaman:** Item → tab State Logs · **Model:** `App\Models\ItemStateLog`

## Kapan Digunakan

Item State Log mencatat perubahan **permanen**:

| Event | Keterangan |
|-------|------------|
| `transfer` | Transfer lokasi/departemen/ruang permanen |
| `assignment` | Perubahan penanggung jawab |
| `status_change` | Perubahan status operasional |

Lihat [ItemStateEventType](/{{route}}/{{version}}/referensi/enum#itemstateeventtype).

## Perbedaan dengan Peminjaman

| Aspek | State Log | Borrowing |
|-------|-----------|-----------|
| Sifat | Permanen | Sementara |
| Restore posisi | Tidak | Ya (check-in) |
| Use case | Mutasi aset, pensiun | Event, pinjam sementara |

## Data

| Field | Keterangan |
|-------|------------|
| `event_type` | transfer / assignment / status_change |
| `from_location_id` → `to_location_id` | Perubahan lokasi |
| `from_department_id` → `to_department_id` | Perubahan departemen |
| `from_room_id` → `to_room_id` | Perubahan ruang |
| `from_user_id` → `to_user_id` | Perubahan penanggung jawab |
| `from_status` → `to_status` | Perubahan status |
| `item_audit_id` | Audit terkait (opsional) |
| `maintenance_id` | Maintenance terkait (opsional) |
| `notes` | Keterangan |

## Pemicu Otomatis

State log dapat dibuat saat:

- Transfer aset antar lokasi
- Perubahan status dari audit atau maintenance
- Assignment penanggung jawab baru

## Activity Log

Selain state log, perubahan model juga tercatat di Spatie Activity Log (`ActivityLogResource`).

## Langkah Selanjutnya

- [Data Master](/{{route}}/{{version}}/modul/data-master)
- [Referensi Database](/{{route}}/{{version}}/referensi/database#item_state_logs)

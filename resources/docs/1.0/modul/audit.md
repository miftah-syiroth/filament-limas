# Audit

Modul audit fisik untuk memverifikasi keberadaan, kondisi, dan lokasi aset.

**Resource:** `ItemAuditResource` · **Model:** `App\Models\ItemAudit`

## Halaman

Resource ini menggunakan pola **Manage** (list + view, tanpa create terpisah di resource). Audit baru dibuat dari sub-halaman Item.

## Data Audit

| Field | Keterangan |
|-------|------------|
| `item_id` | Item yang diaudit |
| `status` | `passed` atau `failed` |
| `condition` | Kondisi fisik — lihat [ItemAuditCondition](/{{route}}/{{version}}/referensi/enum#itemauditcondition) |
| `result` | Hasil audit — lihat [ItemAuditResult](/{{route}}/{{version}}/referensi/enum#itemauditresult) |
| `location_verified` | Lokasi fisik sesuai catatan |
| `audited_at` | Waktu audit |
| `next_audit_at` | Jadwal audit berikutnya |
| `notes` | Catatan auditor |

## Jadwal Audit

Interval audit default diambil dari `models.audit_interval` (bulan). Field pada item:

- `last_audit_date`
- `next_audit_date`

Dashboard menampilkan peringatan item dengan audit jatuh tempo (lewat atau ≤ 7 hari).

## Membuat Audit

1. Buka Item → tab **Audits**
2. Klik buat audit baru
3. Isi kondisi, hasil, verifikasi lokasi
4. Sistem memperbarui `last_audit_date` dan `next_audit_date` pada item

## Ekspor

`ItemAuditExporter` — ekspor data audit ke spreadsheet.

## Relasi

- Audit dapat memicu **Maintenance** (jika hasil `needs_maintenance`)
- Tercatat di **Item State Logs** jika ada perubahan status/lokasi

## Langkah Selanjutnya

- [Maintenance](/{{route}}/{{version}}/modul/maintenance)
- [Referensi Database](/{{route}}/{{version}}/referensi/database#item_audits)

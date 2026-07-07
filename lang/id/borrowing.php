<?php

return [

    'model_label' => 'Peminjaman',
    'plural_model_label' => 'Peminjaman',
    'navigation_label' => 'Peminjaman',

    'statuses' => [
        'active' => 'Aktif',
        'returned' => 'Dikembalikan',
    ],

    'form' => [
        'add' => 'Tambah Peminjaman',
        'section_borrower' => 'Peminjam',
        'section_items' => '',
        'user' => 'Peminjam',
        'to_location' => 'Lokasi tujuan',
        'to_department' => 'Departemen tujuan',
        'to_room' => 'Ruang tujuan',
        'borrowed_at' => 'Tanggal peminjaman',
        'due_at' => 'Batas peminjaman',
        'returned_at' => 'Tanggal pengembalian',
        'notes' => 'Catatan',
        'item' => 'Item',
        'quantity' => 'Jumlah',
        'condition_out' => 'Kondisi keluar',
        'add_item_repeater' => 'Tambah item',
    ],

    'infolist' => [
        'section_general' => 'Informasi umum',
        'borrower' => 'Peminjam',
        'to_location' => 'Lokasi tujuan',
        'to_department' => 'Departemen tujuan',
        'to_room' => 'Ruang tujuan',
        'status' => 'Status',
        'fieldset_dates' => 'Tanggal',
        'borrowed_at' => 'Tanggal peminjaman',
        'due_at' => 'Batas peminjaman',
        'returned_at' => 'Tanggal pengembalian',
        'notes' => 'Catatan',
    ],

    'table' => [
        'borrower' => 'Peminjam',
        'borrowed_at' => 'Tanggal peminjaman',
        'due_at' => 'Batas peminjaman',
        'returned_at' => 'Tanggal pengembalian',
        'items_count' => 'Item',
        'status' => 'Status',
        'to_location' => 'Lokasi tujuan',
        'to_department' => 'Departemen tujuan',
        'to_room' => 'Ruang tujuan',
        'overdue' => 'Terlambat',
        'created_at' => 'Dibuat pada',
        'deleted_at' => 'Dihapus pada',
    ],

    'filters' => [
        'status' => 'Status',
        'overdue' => 'Terlambat',
        'overdue_placeholder' => 'Semua',
        'overdue_true' => 'Ya',
        'overdue_false' => 'Tidak',
    ],

    'notifications' => [
        'created' => 'Pinjaman berhasil dibuat.',
        'invalid_quantities_title' => 'Jumlah pinjaman tidak valid',
        'invalid_quantities_body' => 'Isi jumlah minimal 1 untuk item: :items',
    ],

    'relation' => [
        'table_heading' => 'Item',
        'add_item' => 'Tambah item',
        'item' => 'Item',
        'quantity' => 'Jumlah',
        'borrowable_quantity' => 'Jumlah yang dapat dipinjam',
        'checked_out_at' => 'Tanggal keluar',
        'condition_out' => 'Kondisi keluar',
        'checked_in_at' => 'Tanggal masuk',
        'condition_in' => 'Kondisi masuk',
        'notes' => 'Catatan',
        'created_at' => 'Dibuat pada',
        'deleted_at' => 'Dihapus pada',
        'serial_number' => 'Nomor seri',
        'model' => 'Model',
        'modal_fieldset_out' => 'Keluar',
        'modal_fieldset_in' => 'Masuk',
        'modal_date' => 'Tanggal',
    ],
];

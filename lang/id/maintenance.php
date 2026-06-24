<?php

return [

    'model_label' => 'Pemeliharaan',
    'plural_model_label' => 'Pemeliharaan',
    'navigation_label' => 'Pemeliharaan',

    'statuses' => [
        'reported' => 'Dilaporkan',
        'in_progress' => 'Sedang berjalan',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ],

    'types' => [
        'preventive' => 'Preventif',
        'repair' => 'Perbaikan',
        'upgrade' => 'Upgrade',
        'inspection' => 'Inspeksi',
    ],

    'filters' => [
        'status' => 'Status',
        'type' => 'Tipe',
    ],

    'infolist' => [
        'item' => 'Item',
        'type' => 'Tipe',
        'reported_at' => 'Laporan masuk',
        'started_at' => 'Mulai pemeliharaan',
        'completed_at' => 'Selesai pemeliharaan',
        'cost' => 'Biaya',
        'status' => 'Status',
        'audit_code' => 'Kode audit',
        'notes' => 'Catatan',
    ],

    'table' => [
        'item' => 'Nomor Seri',
        'model' => 'Model',
        'category' => 'Kategori',
        'type' => 'Tipe',
        'reported_at' => 'Laporan',
        'started_at' => 'Mulai',
        'completed_at' => 'Selesai',
        'cost' => 'Biaya',
        'status' => 'Status',
        'audit' => 'Audit',
    ],

    'actions' => [
        'export' => 'Ekspor',
    ],
];

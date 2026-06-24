<?php

return [

    'model_label' => 'Penyusutan',
    'plural_model_label' => 'Penyusutan',
    'navigation_label' => 'Penyusutan',

    'form' => [
        'add' => 'Tambah penyusutan',
        'name' => 'Nama',
        'months' => 'Masa manfaat (bulan)',
        'minimum_value' => 'Batas Nilai Terendah (%)',
        'minimum_value_helper' => 'Jika harga awal adalah 100.000 dan batas harga terendah adalah 20.000, maka batas nilai terendah adalah 20%.',
        'method' => 'Metode',
        'notes' => 'Catatan',
    ],

    'infolist' => [
        'id' => 'ID',
        'name' => 'Nama',
        'months' => 'Masa manfaat (bulan)',
        'minimum_value' => 'Batas Nilai Terendah (%)',
        'method' => 'Metode',
        'notes' => 'Catatan',
        'created_at' => 'Dibuat pada',
        'updated_at' => 'Diperbarui pada',
    ],

    'table' => [
        'name' => 'Nama',
        'months' => 'Masa manfaat (bulan)',
        'minimum_value' => 'Nilai Terendah',
        'method' => 'Metode',
        'models_count' => 'Jumlah Model',
        'created_at' => 'Dibuat pada',
        'updated_at' => 'Diperbarui pada',
    ],

    'enums' => [
        'method' => [
            'amount' => 'Garis lurus',
        ],
    ],

];

<?php

return [

    'model_label' => 'Depreciation',
    'plural_model_label' => 'Depreciations',
    'navigation_label' => 'Depreciation',

    'form' => [
        'name' => 'Name',
        'months' => 'Useful life (months)',
        'minimum_value' => 'Maximum depreciation (%)',
        'method' => 'Method',
        'notes' => 'Notes',
    ],

    'infolist' => [
        'id' => 'ID',
        'name' => 'Name',
        'months' => 'Useful life (months)',
        'minimum_value' => 'Maximum depreciation (%)',
        'method' => 'Method',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
    ],

    'table' => [
        'name' => 'Name',
        'months' => 'Useful life (months)',
        'minimum_value' => 'Maximum depreciation',
        'method' => 'Method',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
    ],

    'enums' => [
        'method' => [
            'amount' => 'Straight-line',
        ],
    ],

];

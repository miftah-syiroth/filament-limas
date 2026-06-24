<?php

return [

    'model_label' => 'Depreciation',
    'plural_model_label' => 'Depreciations',
    'navigation_label' => 'Depreciation',

    'form' => [
        'name' => 'Name',
        'months' => 'Useful life (months)',
        'minimum_value' => 'Final value (%)',
        'minimum_value_helper' => 'If the initial price is 100.000 and the final price is 20.000, then the final value is 20%.',
        'method' => 'Method',
        'notes' => 'Notes',
    ],

    'infolist' => [
        'id' => 'ID',
        'name' => 'Name',
        'months' => 'Useful life (months)',
        'minimum_value' => 'Final value (%)',
        'method' => 'Method',
        'notes' => 'Notes',
    ],

    'table' => [
        'name' => 'Name',
        'months' => 'Useful life (months)',
        'minimum_value' => 'Final value',
        'method' => 'Method',
        'models_count' => 'Models Count',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
    ],

    'enums' => [
        'method' => [
            'amount' => 'Straight-line',
        ],
    ],

];

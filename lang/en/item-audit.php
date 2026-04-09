<?php

return [

    'model_label' => 'Item audit',
    'plural_model_label' => 'Item audits',
    'navigation_label' => 'Item audits',

    'conditions' => [
        'excellent' => 'Excellent',
        'good' => 'Good',
        'fair' => 'Fair',
        'poor' => 'Poor',
        'unusable' => 'Unusable',
    ],

    'results' => [
        'ok' => 'OK',
        'needs_maintenance' => 'Needs maintenance',
        'needs_replacement' => 'Needs replacement',
        'dispose' => 'Dispose',
    ],

    'filters' => [
        'condition' => 'Condition',
        'result' => 'Result',
    ],

    'infolist' => [
        'id' => 'ID',
        'item' => 'Item',
        'status' => 'Status',
        'location_verified' => 'Location verified',
        'notes' => 'Notes',
        'audited_at' => 'Audited at',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'deleted_at' => 'Deleted at',
        'next_audit_at' => 'Next audit',
        'condition' => 'Condition',
        'result' => 'Result',
    ],

    'table' => [
        'code' => 'Code',
        'item' => 'Item',
        'audited_at' => 'Audit date',
        'next_audit_at' => 'Next audit',
        'location_verified' => 'Location matches',
        'condition' => 'Condition',
        'result' => 'Result',
    ],

    'actions' => [
        'export' => 'Export',
    ],
];

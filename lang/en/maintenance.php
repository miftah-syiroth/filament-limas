<?php

return [

    'model_label' => 'Maintenance',
    'plural_model_label' => 'Maintenances',
    'navigation_label' => 'Maintenances',

    'statuses' => [
        'reported' => 'Reported',
        'in_progress' => 'In progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'types' => [
        'preventive' => 'Preventive',
        'repair' => 'Repair',
        'upgrade' => 'Upgrade',
        'inspection' => 'Inspection',
    ],

    'filters' => [
        'status' => 'Status',
        'type' => 'Type',
    ],

    'infolist' => [
        'item' => 'Item',
        'type' => 'Type',
        'reported_at' => 'Report received',
        'started_at' => 'Started',
        'completed_at' => 'Completed',
        'cost' => 'Cost',
        'status' => 'Status',
        'audit_code' => 'Audit code',
        'notes' => 'Notes',
    ],

    'table' => [
        'item' => 'Item',
        'type' => 'Type',
        'reported_at' => 'Report',
        'started_at' => 'Started',
        'completed_at' => 'Completed',
        'cost' => 'Cost',
        'status' => 'Status',
        'audit' => 'Audit',
    ],

    'actions' => [
        'export' => 'Export',
    ],
];

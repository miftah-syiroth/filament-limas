<?php

return [

    'model_label' => 'Activity log',
    'plural_model_label' => 'Activity logs',
    'navigation_label' => 'Activity logs',

    'form' => [
        'log_name' => 'Log name',
        'description' => 'Description',
        'subject_type' => 'Subject type',
        'subject_id' => 'Subject ID',
        'causer_type' => 'Causer type',
        'causer_id' => 'Causer ID',
        'properties' => 'Properties',
        'event' => 'Event',
        'batch_uuid' => 'Batch UUID',
    ],

    'infolist' => [
        'id' => 'ID',
        'log_name' => 'Log name',
        'description' => 'Description',
        'subject_type' => 'Subject type',
        'subject_id' => 'Subject ID',
        'causer_type' => 'Causer type',
        'causer_id' => 'Causer ID',
        'event' => 'Event',
        'created_at' => 'Created at',
    ],

    'auth' => [
        'login' => 'Logged in via :method',
        'logout' => 'Logged out',
        'methods' => [
            'password' => 'password',
            'sso' => 'SSO',
        ],
    ],

    'table' => [
        'causer_name' => 'Name',
        'causer_email' => 'Email',
        'event' => 'Event',
        'subject_type' => 'Subject type',
        'subject_record' => 'Record',
        'properties' => 'Properties',
        'created_at' => 'Created at',
    ],
];

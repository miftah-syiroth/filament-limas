<?php

return [

    'model_label' => 'User',
    'plural_model_label' => 'Users',
    'navigation_label' => 'Users',

    'form' => [
        'add' => 'Add User',
        'name' => 'Name',
        'email' => 'Email address',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'email_verified' => 'Email verified',
    ],

    'infolist' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email address',
        'email_verified_at' => 'Email verified at',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'two_factor_secret' => 'Two-factor secret',
        'two_factor_recovery_codes' => 'Two-factor recovery codes',
        'two_factor_confirmed_at' => 'Two-factor confirmed at',
    ],

    'table' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email address',
        'email_verified' => 'Verified',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'two_factor_confirmed_at' => 'Two-factor confirmed at',
        'roles' => 'Roles',
        'no_role' => 'Without Role',
    ],
];

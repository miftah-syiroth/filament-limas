<?php

return [

    'model_label' => 'Borrowing',
    'plural_model_label' => 'Borrowings',
    'navigation_label' => 'Borrowings',

    'statuses' => [
        'active' => 'Active',
        'returned' => 'Returned',
    ],

    'form' => [
        'add' => 'Add Borrowing',
        'section_borrower' => 'Borrower',
        'section_items' => '',
        'user' => 'Borrower',
        'to_location' => 'Destination location',
        'to_department' => 'Destination department',
        'to_room' => 'Destination room',
        'borrowed_at' => 'Borrowed on',
        'due_at' => 'Due date',
        'returned_at' => 'Returned on',
        'notes' => 'Notes',
        'item' => 'Item',
        'quantity' => 'Quantity',
        'condition_out' => 'Condition (out)',
        'add_item_repeater' => 'Add item',
    ],

    'infolist' => [
        'section_general' => 'General information',
        'borrower' => 'Borrower',
        'to_location' => 'Destination location',
        'to_department' => 'Destination department',
        'to_room' => 'Destination room',
        'status' => 'Status',
        'fieldset_dates' => 'Dates',
        'borrowed_at' => 'Borrowed on',
        'due_at' => 'Due date',
        'returned_at' => 'Returned on',
        'notes' => 'Notes',
    ],

    'table' => [
        'borrower' => 'Borrower',
        'borrowed_at' => 'Borrowed on',
        'due_at' => 'Due date',
        'returned_at' => 'Returned on',
        'items_count' => 'Items',
        'status' => 'Status',
        'to_location' => 'Destination location',
        'to_department' => 'Destination department',
        'to_room' => 'Destination room',
        'overdue' => 'Overdue',
        'created_at' => 'Created at',
        'deleted_at' => 'Deleted at',
    ],

    'filters' => [
        'status' => 'Status',
        'overdue' => 'Overdue',
        'overdue_placeholder' => 'All',
        'overdue_true' => 'Yes',
        'overdue_false' => 'No',
    ],

    'notifications' => [
        'created' => 'Borrowing created successfully.',
        'invalid_quantities_title' => 'Invalid borrowing quantities',
        'invalid_quantities_body' => 'Enter a quantity of at least 1 for: :items',
    ],

    'relation' => [
        'table_heading' => 'Items',
        'add_item' => 'Add item',
        'item' => 'Item',
        'quantity' => 'Quantity',
        'borrowable_quantity' => 'Borrowable quantity',
        'checked_out_at' => 'Checked out on',
        'condition_out' => 'Condition (out)',
        'checked_in_at' => 'Checked in on',
        'condition_in' => 'Condition (in)',
        'notes' => 'Notes',
        'created_at' => 'Created at',
        'deleted_at' => 'Deleted at',
        'serial_number' => 'Serial number',
        'model' => 'Model',
        'modal_fieldset_out' => 'Checked out',
        'modal_fieldset_in' => 'Checked in',
        'modal_date' => 'Date',
        'return_items' => 'Return All',
        'return_items_success' => 'All items returned successfully.',
        'return_items_failure' => 'Failed to return :successCount of :totalCount items.',
    ],
];

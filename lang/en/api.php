<?php

return [

    // Authentication messages
    'auth' => [
        'invalid_credentials' => 'Invalid credentials.',
        'unauthorized' => 'Unauthorized.',
        'forbidden' => 'Forbidden.',
        'forbidden_scope' => 'Forbidden: missing required scope :scope.',
    ],

    // Admin
    'admin' => [
        'login_success' => 'Admin authenticated successfully.',
        'client' => [
            'created' => 'Client created successfully.',
            'name_required' => 'Name is required.',
            'email_required' => 'Email is required.',
            'email_valid' => 'Email must be valid.',
            'email_unique' => 'Email is already taken.',
            'status_invalid' => 'Status must be active or inactive.',
            'listed' => 'Clients retrieved successfully.',
            'found' => 'Client retrieved successfully.',
            'updated' => 'Client updated successfully.',
            'deleted' => 'Client deleted successfully.',
            'validation' => [
                'name_required'  => 'The name field is required.',
                'name_string'    => 'The name must be a string.',
                'name_max'       => 'The name may not be greater than 255 characters.',
                'email_required' => 'The email field is required.',
                'email_email'    => 'The email must be a valid email address.',
                'email_unique'   => 'The email has already been taken.',
            ],
            'password_required' => 'The password is required.',
            'password_min'      => 'The password must be at least 8 characters.',
            'user' => [
                'email_required'      => 'Email is required.',
                'email_valid'         => 'Email must be a valid email address.',
                'password_required'   => 'Password is required.',
                'password_min'        => 'Password must be at least 8 characters.',
                'client_required'     => 'Client ID is required.',
                'client_exists'       => 'Client does not exist.',
                'invalid_credentials' => 'Invalid credentials.',
            ],
        ]
    ],

    'client' => [
        'invalid_tenant' => 'Invalid tenant ID.',
        'login_success' => 'Login successful.',
        'invalid_credentials' => 'Invalid credentials.',
        'tenant_required' => 'Tenant ID is required.',
        'tenant_invalid'  => 'Invalid tenant ID.',
    ],

    'orders' => [
        'created' => 'Order created successfully.',
        'items_required' => 'The items field is required.',
        'items_array' => 'Items must be an array.',
        'items_min' => 'At least one item is required.',
        'item_name_required' => 'Each item must have a name.',
        'item_quantity_min' => 'Item quantity must be at least 1.',
        'item_price_min' => 'Item price must be at least 0.',
    ],

];

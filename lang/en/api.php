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
        ]
    ],

];

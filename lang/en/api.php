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
        ]
    ],

];

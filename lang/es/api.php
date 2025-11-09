<?php

return [

    'auth' => [
        'invalid_credentials' => 'Credenciales inválidas.',
        'unauthorized' => 'No autorizado.',
        'forbidden' => 'Prohibido.',
        'forbidden_scope' => 'Prohibido: falta el permiso requerido :scope.',
    ],

    'admin' => [
        'login_success' => 'Autenticación de administrador exitosa.',
        'client' => [
            'created' => 'Cliente creado exitosamente.',
            'name_required' => 'El nombre es obligatorio.',
            'email_required' => 'El correo es obligatorio.',
            'email_valid' => 'El correo debe ser válido.',
            'email_unique' => 'El correo ya está en uso.',
            'status_invalid' => 'El estado debe ser activo o inactivo.',
            'listed' => 'Cliente(s) recuperado(s) satisfactoriamente.',
            'found' => 'Cliente recuperado satisfactoriamente.',
            'updated' => 'Cliente actualizado satisfactoriamente.',
            'deleted' => 'Cliente eliminado correctamente.',
            'validation' => [
                'name_required'  => 'El campo nombre es obligatorio.',
                'name_string'    => 'El nombre debe ser una cadena de texto.',
                'name_max'       => 'El nombre no puede tener más de 255 caracteres.',
                'email_required' => 'El campo correo electrónico es obligatorio.',
                'email_email'    => 'El correo electrónico debe ser una dirección válida.',
            ],
             'password_required' => 'La contraseña es obligatoria.',
            'password_min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ]
    ],

];

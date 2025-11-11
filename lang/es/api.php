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
            'user' => [
                'email_required'      => 'El correo electrónico es obligatorio.',
                'email_valid'         => 'El correo electrónico debe ser válido.',
                'password_required'   => 'La contraseña es obligatoria.',
                'password_min'        => 'La contraseña debe tener al menos 8 caracteres.',
                'client_required'     => 'El ID del cliente es obligatorio.',
                'client_exists'       => 'El cliente no existe.',
                'invalid_credentials' => 'Credenciales inválidas.',
            ],
        ]
    ],

    'client' => [
        'invalid_tenant' => 'ID de cliente no válido.',
        'login_success' => 'Inicio de sesión exitoso.',
        'invalid_credentials' => 'Credenciales inválidas.',
        'tenant_required' => 'El ID del cliente (tenant) es obligatorio.',
        'tenant_invalid'  => 'El ID del cliente (tenant) es inválido.',
    ],

    'orders' => [
        'created' => 'Orden creada correctamente.',
        'items_required' => 'El campo items es obligatorio.',
        'items_array' => 'Items debe ser un array.',
        'items_min' => 'Se requiere al menos un item.',
        'item_name_required' => 'Cada item debe tener un nombre.',
        'item_quantity_min' => 'La cantidad mínima es 1.',
        'item_price_min' => 'El precio mínimo es 0.',
        'found'        => 'Orden recuperada correctamente.',
        'not_found'    => 'Orden no encontrada.',
        'listed' => 'Órdenes listadas correctamente.',
        'customer_name_required'   => 'El nombre del cliente es obligatorio.',
        'customer_email_invalid'   => 'El correo electrónico del cliente no es válido.',
    ],



];

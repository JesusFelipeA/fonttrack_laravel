<?php

/*
| Mensajes de validación en español para FontTrack.
| Cubre las reglas que usan los controladores del proyecto; cualquier regla no
| listada aquí usa el mensaje en inglés de Laravel (fallback_locale = en).
*/

return [
    'accepted' => 'El campo :attribute debe ser aceptado.',
    'array' => 'El campo :attribute debe ser una lista.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña actual es incorrecta.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'exists' => 'El :attribute seleccionado no es válido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'image' => 'El campo :attribute debe ser una imagen.',
    'in' => 'El :attribute seleccionado no es válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'json' => 'El campo :attribute debe ser un texto JSON válido.',
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'file' => 'El archivo :attribute no debe pesar más de :max kilobytes.',
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
        'array' => 'El campo :attribute no debe tener más de :max elementos.',
    ],
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'file' => 'El archivo :attribute debe pesar al menos :min kilobytes.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'array' => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'numeric' => 'El campo :attribute debe ser un número.',
    'required' => 'El campo :attribute es obligatorio.',
    'same' => 'Los campos :attribute y :other deben coincidir.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'El :attribute ya está registrado.',
    'uploaded' => 'No se pudo subir el archivo :attribute.',

    'custom' => [],

    // Nombres legibles de los campos del proyecto.
    'attributes' => [
        'nombre' => 'nombre',
        'correo' => 'correo',
        'password' => 'contraseña',
        'current_password' => 'contraseña actual',
        'tipo_usuario' => 'tipo de usuario',
        'foto_usuario' => 'foto',
        'id_lugar' => 'lugar',
        'estado' => 'estado',
        'clave_material' => 'clave de material',
        'descripcion' => 'descripción',
        'generico' => 'genérico',
        'clasificacion' => 'clasificación',
        'existencia' => 'existencia',
        'costo_promedio' => 'costo promedio',
        'cantidad' => 'cantidad',
        'archivo_cardex' => 'archivo del Kardex',
        'excel_file' => 'archivo Excel',
        'lugar_import' => 'lugar',
        'eco' => 'número ECO',
        'placas' => 'placas',
        'marca' => 'marca',
        'modelo' => 'modelo',
        'anio' => 'año',
        'kilometraje' => 'kilometraje',
        'color' => 'color',
        'conductor_habitual' => 'conductor habitual',
        'estatus' => 'estado',
        'correo_destino' => 'correo de destino',
        'comentarios' => 'comentarios',
        'usuario_reporta_id' => 'usuario que reporta',
        'nombre_usuario_reporta' => 'nombre de quien reporta',
        'correo_usuario_reporta' => 'correo de quien reporta',
        'materials' => 'materiales',
    ],
];

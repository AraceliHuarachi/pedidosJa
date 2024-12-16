<?php

namespace App\traits;

trait SimplifyValidationErrors
{
    public function simplifyErrorMessages(array $errors)
    {
        foreach ($errors as $key => $messages) {
            // Extraer el nombre del campo después del último punto
            if (preg_match('/\.([^.]+)$/', $key, $matches)) {
                $fieldName = $matches[1];
            } else {
                $fieldName = $key;
            }

            // Reemplazar guiones bajos con espacios para mejorar la legibilidad
            $readableFieldName = str_replace('_', ' ', $fieldName);

            // Reemplazar el nombre completo del campo con el nombre simplificado
            foreach ($messages as $message) {
                // Si ya existe una entrada para este campo, agregar el siguiente mensaje en un array
                if (isset($simplified[$key])) {
                    $simplified[$key][] = str_replace($key, $readableFieldName, $message);
                } else {
                    $simplified[$key] = [str_replace($key, $readableFieldName, $message)];
                }
            }
        }

        return $simplified;
    }
}

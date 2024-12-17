<?php

namespace App\traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

trait SimplifyValidationErrors
{
    // Metodo para simplificar los mensajes de error.
    public function simplifyErrorMessages(array $errors)
    {
        $simplified = [];

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
    // Metodo para simplificar mensajes de errores dentro de un FormRequest
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->getMessages(); // Obtiene los errores originales
        $simplifiedErrors = $this->simplifyErrorMessages($errors); // simplifica los mensajes de error

        throw new HttpResponseException(
            response()->json($simplifiedErrors, 422) // Devuelve los mensajes modificados
        );
    }
    // Metodo para simplificar errores cuando se usa Validator directamente
    public function forLooseValidations(Validator $validator)
    {
        $errors = $validator->errors()->getMessages();
        $simplifiedErrors = $this->simplifyErrorMessages($errors);

        return response()->json($simplifiedErrors, 422);
    }
}

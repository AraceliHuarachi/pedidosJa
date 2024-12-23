<?php

namespace App\Services;

class FieldTranslator
{
    public function translate(string $fieldName): string
    {
        $locale = config('app.locale'); // Obtener el idioma actual.

        // Intentar traducir el nombre del campo.
        if ($locale === 'es') {
            $translation = __('attributes.' . $fieldName, [], $locale);

            // Si la traducción no se encuentra, devuelve el nombre original.
            if ($translation === 'attributes.' . $fieldName) {
                return $fieldName;
            }

            return $translation;
        }

        // Para otros idiomas, devolver el nombre del campo tal cual.
        return $fieldName;
    }
}

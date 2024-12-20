<?php

namespace App\Services;

class FieldTranslator
{
    public function translate(string $fieldName): string
    {
        $locale = config('app.locale'); // Get the current locale

        // If locale is 'es', attempt to translate. Otherwise, return the field name as-is.
        if ($locale === 'es') {
            return __('attributes.' . $fieldName, [], $locale) ?? $fieldName;
        }

        // Return the field name as-is for other locales (e.g., 'en').
        return $fieldName;
    }
}
